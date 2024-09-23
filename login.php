<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <title>Smart Inventory</title>
    <?php include 'head.php'; ?>
  </head>
  <body>
  <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5">
                <h4 class="font-weight-bold">Login</h4>
                <h6 class="font-weight-light">Sign in to continue..</h6>
                <form class="pt-3" action="/login" method="POST">
                  <div class="form-group">
                    <input
                      type="email"
                      class="form-control form-control-lg"
                      id="exampleInputEmail1"
                      name="email"
                      placeholder="Username"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <input
                      type="password"
                      class="form-control form-control-lg"
                      id="exampleInputPassword1"
                      name="password"
                      placeholder="Password"
                      required
                    />
                  </div>
                  <div class="mt-3 d-grid gap-2">
                    <button
                      type="submit"
                      class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn"
                    >
                      SIGN IN
                    </button>
                  </div>
                  <div
                    class="my-2 d-flex justify-content-between align-items-center"
                  >
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          name="remember"
                        />
                        Keep me signed in
                      </label>
                    </div>
                    <a href="#" class="auth-link text-primary"
                      >Forgot password?</a
                    >
                  </div>
                  <div class="mb-2 d-grid gap-2">
                    <button
                      type="button"
                      class="btn btn-block btn-google auth-form-btn"
                    >
                      <i class="mdi mdi-google me-2"></i>Connect using Google
                    </button>
                  </div>
                  <div class="text-center mt-4 font-weight-light">
                    Don't have an account?
                    <a href="register.html" class="text-primary">Create</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
  </body>
</html>
