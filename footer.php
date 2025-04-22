<footer class="footer">
    <div class="d-sm-flex justify-content-center font-weight-bold">
        <div class="animation-section">
            <span class="static-txt">Mini Project <icon class="fa fa-code"></icon> Made with 💖 by </span>
            <span class="dynamic-txt"></span>
        </div>
    </div>
    <p class="mb-0 small text-center mt-2">&copy; 2024 Smart Inventory Group. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="assets/vendors/js/vendor.bundle.base.js"></script>
<script src="assets/vendors/chart.js/chart.umd.js"></script>
<script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="assets/js/off-canvas.js"></script>
<script src="assets/js/misc.js"></script>
<script src="assets/js/settings.js"></script>
<script src="assets/js/todolist.js"></script>
<script src="assets/js/jquery.cookie.js"></script>
<script src="assets/js/dashboard.js"></script>

<script>
// JavaScript for Typewriting Effect
const names = ["Nitin Govardhane", "Vedanti Lakade", "Jitesh Borse"];
let currentIndex = 0;
let charIndex = 0;
const speed = 150;
const eraseSpeed = 100; 
const delayBetweenNames = 2000; 

function type() {
    const dynamicTextElement = document.querySelector('.dynamic-txt');
    if (charIndex < names[currentIndex].length) {
        dynamicTextElement.innerHTML += names[currentIndex].charAt(charIndex);
        charIndex++;
        setTimeout(type, speed);
    } else {
        setTimeout(erase, delayBetweenNames);
    }
}

function erase() {
    const dynamicTextElement = document.querySelector('.dynamic-txt');
    if (charIndex > 0) {
        dynamicTextElement.innerHTML = names[currentIndex].substring(0, charIndex - 1);
        charIndex--;
        setTimeout(erase, eraseSpeed);
    } else {
        currentIndex++;
        if (currentIndex >= names.length) {
            currentIndex = 0;
        }
        setTimeout(type, speed);
    }
}

// Start the typing effect
document.addEventListener("DOMContentLoaded", function() {
    if (document.querySelector('.dynamic-txt')) {
        setTimeout(type, delayBetweenNames);
    }
});
</script>

<style>
.footer {
    padding-top: 10px;  
    padding-bottom: 10px;
    font-size: 0.9rem; 
    border: none;
}

/* Animation Section Styles */
.animation-section {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: row;
    gap: 10px; 
    font-size: 1.1rem;
    text-align: center;
    margin-top: 10px;
    flex-wrap: wrap; /* Allow wrapping on smaller screens */
}

.static-txt {
    font-weight: 400;
    color: #000;
}

.dynamic-txt {
    color: #fc6d6d;
    font-weight: 500;
    border-right: 2px solid #fc6d6d;
    white-space: nowrap;
    overflow: hidden;
}

/* Media queries for smaller screens */
@media screen and (max-width: 576px) {
    .animation-section {
        font-size: 0.9rem;
        gap: 5px;
    }
    
    .footer {
        font-size: 0.8rem;
    }
}

@media screen and (max-width: 320px) {
    .animation-section {
        font-size: 0.8rem;
    }
}
</style>
