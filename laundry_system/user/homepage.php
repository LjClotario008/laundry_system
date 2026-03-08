<?php 
session_start(); // Keeps the user logged in as they browse
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Fresh Wave - Laundry Service System</title>
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="nav_logo">
        <a href="index.php">
          <img src="image/orig.logo.png" alt="laundry_Logo">
          <h2>Laundry Service System</h2>
        </a>
      </div>

     <div class="navdiv">
    <ul>
        <li><a href="#hero-section">Home</a></li>
        <li><a href="#about-section">About</a></li>
        <li><a href="#contact-section">Contact</a></li>
        <li><a href="#pricing-section">Service</a></li>

        <?php if(!isset($_SESSION['user_id'])): ?>
            <a href="login.php">
                <button type="button" class="nav_btn">SignIn</button>
            </a>
            <a href="register.php">
                <button type="button" class="nav_btn">SignUp</button>
            </a>
        <?php endif; ?>
    </ul>
</div>
    </nav>
  </header>

  <main>
    <section class="hero_section" id="hero-section">
      <div class="section1-container">
        <div class="hero_container">
            <div class="main_section">
                <h2>Book your Laundry</h2>
                <h3>Life is short. Don't spend it doing laundry.</h3>
                <p>Life gets busy—laundry shouldn't be a burden. Log in to experience a simple, reliable, and efficient laundry service.</p>
                
              <div class="hero_section_button">
                  <?php if(isset($_SESSION['user_id'])): ?>
                      <a href="login.php" class="button order-now">Book Now</a>
                  <?php else: ?>
                      <a href="login.php?redirect=booking.php" class="button order-now">Book Now</a>
                  <?php endif; ?>
              </div>
            </div>
        </div>

        <div class="laundry-image">
          <img src="image/trust.png" alt="tshirt">
        </div>
      </div>
    </section>
  </main>

  <section class="about_us" id="about-section">
      <div class="section_container">
        <div class="about_us_container">
          <div class="text_section">
            <h2 class="section_title">About Us</h2>
            <p class="section_description">At Fresh Wave, we believe that clean clothes are the foundation of a clear mind. We've turned the tide on traditional chores by providing a high-tech, high-touch laundry experience that flows with your busy lifestyle...</p>
          </div>
          <div class="image_section">
            <img src="image/washing.png" alt="about_us">
          </div>
        </div>
      </div>
  </section>

  <section class="pricing_section" id="pricing-section">
    <h2 class="section_title">Our Service Plans</h2>
    
    <div class="pricing_row">
        
        <div class="price_card">
            <div class="card_header">
                <span class="plan_tag">Basic Wash</span>
                <div class="price_amount">P180.00</div>
                <p>Wash + Dry + Fold</p>
            </div>
            <a href="basic_wash.php" class="price_view_btn">View Basic Wash</a>
            <ul class="features">
                <li><i class="fas fa-check"></i> Up to 8kg mix clothes</li>
                <li><i class="fas fa-check"></i> Standard Detergent</li>
            </ul>
        </div>

        <div class="price_card featured">
            <div class="card_header">
                <span class="plan_tag">Premium Care</span>
                <div class="price_amount">P250.00</div>
                <p>Power Wash + Dry + Fold</p>
            </div>
            <a href="premium.php" class="price_view_btn">View Premium Care</a>
            <ul class="features">
                <li><i class="fas fa-bolt"></i> Deep Cleaning Cycle</li>
                <li><i class="fas fa-check"></i> Heavy Duty Cleaning</li>
                <li><i class="fas fa-star"></i> Fresh Vibes Finish</li>
            </ul>
        </div>

        <div class="price_card">
            <div class="card_header">
                <span class="plan_tag">Specialty</span>
                <div class="price_amount">P180.00</div>
                <p>Household Fabrics</p>
            </div>
            <a href="quick_basic.php" class="price_view_btn">View Specialty</a>
            <ul class="features">
                <li><i class="fas fa-bed"></i> Comforters </li>
                <li><i class="fas fa-scroll"></i> Curtains & Bed Sheets</li>
            </ul>
        </div>
    </div>
</section>  
<section class="contact_section" id="contact-section">
    <h2 class="section_title">Contact Us</h2>
    
    <div class="contact_container_centered">
      <div class="contact_info_grid">
        
        <div class="info_box">
          <i class="fas fa-map-marker-alt"></i>
          <div>
            <h4>Location</h4>
            <p>F. Delima St., Lumbia, CDOC (beside City Hospital - Lumbia)</p>
          </div>
        </div>

        <div class="info_box">
          <i class="fas fa-phone-alt"></i>
          <div>
            <h4>Phone</h4>
            <p>+63 906 253 9318</p>
          </div>
        </div>

        <div class="info_box">
            <a href="https://www.facebook.com/profile.php?id=61564505931750" class="clickable_box" target="_self">
                <i class="fab fa-facebook"></i>
                <h4>Facebook</h4>
                <p>profile.php?id=61564505931750</p>
            </a>
        </div>

        <div class="info_box">
          <i class="fas fa-clock"></i>
          <div>
            <h4>Opening Hours</h4>
            <p>Open Daily: 7:00 AM - 7:00 PM</p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <footer style="text-align: center; padding: 40px; background: rgba(0,0,0,0.3); border-top: 1px solid rgba(255,255,255,0.05); margin-top: 50px;">
    <p>&copy; 2026 Fresh Wave Laundry Service System. All Rights Reserved.</p>
  </footer>
</body> 
</html>