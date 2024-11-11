import pandas as pd
import numpy as np
import os
import plotly.express as px
from sklearn.linear_model import LinearRegression
from datetime import datetime, timedelta
from sqlalchemy import create_engine

# Database connection using SQLAlchemy
def get_database_connection():
    return create_engine('mysql+mysqlconnector://root:@localhost/smart_inventory_db')

# Fetch sales data from the database, joining with InventoryItem to get ItemName
def fetch_sales_data():
    engine = get_database_connection()
    query = """
        SELECT s.TransactionID, s.ItemID, i.ItemName, s.SaleDate, s.QuantitySold, s.TotalPrice 
        FROM SalesTransaction s
        JOIN InventoryItem i ON s.ItemID = i.ItemID;
    """
    df = pd.read_sql(query, con=engine)
    return df

# Pre-process sales data
def process_data(df):
    df['SaleDate'] = pd.to_datetime(df['SaleDate'])
    df['MonthYear'] = df['SaleDate'].dt.to_period('M')  # Group by month and year
    df.fillna(0, inplace=True)
    return df

# Predict future sales using linear regression for each item, month-wise for 6 months
def predict_sales(df):
    model = LinearRegression()
    predictions = []

    # Group data by ItemID and MonthYear to get monthly sales data
    df_monthly = df.groupby(['ItemID', 'MonthYear', 'ItemName']).agg({
        'QuantitySold': 'sum',
        'TotalPrice': 'sum'
    }).reset_index()

    for item_id in df_monthly['ItemID'].unique():
        item_df = df_monthly[df_monthly['ItemID'] == item_id]

        # Check if there is enough data to train the model
        if len(item_df) < 2:  # Need at least 2 data points for regression
            print(f"Skipping ItemID {item_id} due to insufficient data.")
            continue

        # Prepare features and target
        X = item_df['MonthYear'].apply(lambda x: x.ordinal).values.reshape(-1, 1)  # Convert period to ordinal
        y = item_df['QuantitySold']

        # Fit the model for each item separately
        try:
            model.fit(X, y)
        except Exception as e:
            print(f"Error fitting model for ItemID {item_id}: {e}")
            continue

        # Predict for the next 6 months
        future_months = [pd.Period((datetime.now() + timedelta(days=i * 30)).strftime('%Y-%m'), freq='M') for i in range(1, 7)]
        future_months_df = np.array([month.ordinal for month in future_months]).reshape(-1, 1)

        predicted_sales = model.predict(future_months_df)

        # Store the predictions for each item
        item_name = item_df['ItemName'].iloc[0]  # Retrieve the item name for each item
        for i in range(len(future_months)):
            predicted_quantity = max(0, int(predicted_sales[i]))  # Avoid negative predictions

            # Cap the predicted quantity to a maximum reasonable value
            if predicted_quantity > 1000:  # Assuming 1000 as a max threshold
                predicted_quantity = 1000

            prediction = {
                'ItemID': int(item_id),
                'ItemName': item_name,
                'PredictedRestockDate': future_months[i].start_time.strftime('%Y-%m-%d'),
                'PredictedQuantity': predicted_quantity
            }
            predictions.append(prediction)

    # Debugging: Check if predictions were generated properly
    if len(predictions) == 0:
        print("No predictions were generated. Check if data is sufficient.")
    else:
        print(f"Number of predictions generated: {len(predictions)}")

    return predictions

# Plot predictions with historical data
def plot_predictions(df, predictions):
    # Convert predictions to a DataFrame
    future_df = pd.DataFrame(predictions)

    # Check if there are predictions to plot
    if future_df.empty:
        print("No predictions available to plot.")
        return None

    # Ensure 'ItemName' exists in the DataFrame
    if 'ItemName' not in future_df.columns:
        raise KeyError("The 'ItemName' column is missing from the predictions DataFrame.")

    # Plot historical sales data
    fig = px.scatter(df, x='SaleDate', y='TotalPrice', color='ItemName', title='Sales Trends with Future Predictions')

    # Plot future predictions for each item
    for item_name in future_df['ItemName'].unique():
        item_future_df = future_df[future_df['ItemName'] == item_name]
        fig.add_scatter(
            x=item_future_df['PredictedRestockDate'], 
            y=item_future_df['PredictedQuantity'], 
            mode='lines', 
            name=f'Future Predictions - {item_name}'
        )

    plot_path = 'plots/sales_predictions.html'
    fig.write_html(plot_path)
    return plot_path

# Plot cumulative sales over time
def plot_cumulative_sales(df):
    df_sorted = df.sort_values(by='SaleDate')
    df_sorted['CumulativeSales'] = df_sorted.groupby(['ItemID'])['TotalPrice'].cumsum().reset_index(level=0, drop=True)
    fig = px.line(df_sorted, x='SaleDate', y='CumulativeSales', color='ItemName', title='Cumulative Sales Over Time')

    plot_path = 'plots/cumulative_sales.html'
    fig.write_html(plot_path)
    return plot_path

# Plot pie chart showing sales distribution by product name
def plot_sales_distribution(df):
    sales_summary = df.groupby('ItemName').agg({'TotalPrice': 'sum'}).reset_index()
    fig = px.pie(sales_summary, values='TotalPrice', names='ItemName', title='Sales Distribution by Item')

    plot_path = 'plots/sales_distribution.html'
    fig.write_html(plot_path)
    return plot_path

# Main execution block
if __name__ == "__main__":
    # Fetching and processing data
    df = fetch_sales_data()
    processed_df = process_data(df)

    # Generate predictions
    predictions = predict_sales(processed_df)

    # Debugging step: Check a sample of predictions
    if len(predictions) > 0:
        print("Sample prediction: ", predictions[0])

    # Save predictions to JSON for later usage
    predictions_file_path = 'predictions/predicted_restock.json'
    os.makedirs(os.path.dirname(predictions_file_path), exist_ok=True)
    if len(predictions) > 0:
        pd.DataFrame(predictions).to_json(predictions_file_path, orient='records', date_format='iso')

    # Plotting the sales and predictions if available
    if len(predictions) > 0:
        sales_predictions_plot_path = plot_predictions(processed_df, predictions)
        cumulative_plot_path = plot_cumulative_sales(processed_df)
        sales_distribution_plot_path = plot_sales_distribution(processed_df)
    else:
        print("No predictions available, skipping plots.")
