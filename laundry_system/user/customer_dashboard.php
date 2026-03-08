<?php
session_start();
// Temporary fake name - Your group member will connect this to the DB later
$_SESSION['user_name'] = "John Doe"; 
$customerName = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Fresh Wave Laundry</title>
    <link rel="stylesheet" href="customer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
</head>
</head>
        <body>

            <aside class="sidebar">
                <div class="logo">
                    <img src="image/orig.logo.png" alt="laundry_Logo">
                    <h2>Fresh Wave</h2>
                </div>
                <nav>
                    <a href="#" class="active"><i class="fas fa-th-large"></i> Overview</a>
                    <a href="#"><i class="fas fa-plus-circle"></i> Book Order</a>
                    <a href="#"><i class="fas fa-history"></i> My History</a>
                    
                    <div class="user_info_display" style="padding: 15px; margin-top: 20px; border-top: 1px solid rgba(77, 163, 255, 0.1); display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-user-circle" style="color: #ffffff; font-size: 24px;"></i>
                        <div>
                            <p style="font-size: 10px; color: rgba(255,255,255,0.5); text-transform: uppercase; margin: 0;">Logged In as</p>
                            <p style="color: #ffffff; font-weight: 600; margin: 0;"><?php echo $customerName; ?></p> 
                        </div>
                    </div>

                    <a href="homepage.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </nav>
            </aside>

            <main class="main_content" id="content-area">
            </main>

            <script>
            const customerName = "<?php echo $customerName; ?>"; 
            const contentArea = document.getElementById('content-area');
            let userOrders = [];
            let mapInstance = null;

            const pages = {
                overview: `
                    <div class="overview_container">
                        <div class="welcome_msg">
                            <h1>Welcome Back, ${customerName}!</h1>
                            <p>What can we wash for you today?</p>
                            
                        </div>
                        <div class="services_grid">
                            <div class="service_card">
                                <div class="service_icon" style="color: #4da3ff;"><i class="fas fa-map-marker-alt"></i></div>
                                <h3>Track Order</h3>
                                <p>See exactly where your laundry is in real-time.</p>
                                <button class="get_started_btn" onclick="loadPage('trackorder')">View Map</button>
                            </div>
                            <div class="service_card">
                                <div class="service_icon" style="color: #5c0f9b;"><i class="fas fa-tasks"></i></div>
                                <h3>Order Status</h3>
                                <p>Check if your order is Approved or Ready.</p>
                                <button class="get_started_btn" onclick="loadPage('orderstatus')" style="background: #ebca13; color: #111;">Check Status</button>
                            </div>
                            <div class="service_card">
                                <div class="service_icon" style="color: #28a745;"><i class="fas fa-file-invoice-dollar"></i></div>
                                <h3>Order Summary</h3>
                                <p>View receipts and total costs for your items.</p>
                                <button class="get_started_btn" onclick="loadPage('ordersummary')" style="background: #28a745;">View Order</button>
                            </div>
                        </div>
                    </div>
                `,
                trackorder: `
                    <div class="track_container">
                        <button onclick="loadPage('overview')" class="back_btn">
                            <i class="fas fa-chevron-left"></i> Back
                        </button>
                        <h1>Live Tracking</h1>
                        <div id="map" style="height: 350px; width: 100%; border-radius: 15px; margin-top: 20px; border: 2px solid rgba(77, 163, 255, 0.2); z-index: 1;"></div>
                        <div class="track_card" style="background: white; color: #111; padding: 25px; border-radius: 15px; margin-top: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                                <h3 style="color: #001d3d;">Order #FW-1024</h3>
                                <span style="color: #28a745; font-weight: bold;">In Transit</span>
                            </div>
                            <div class="stepper" style="display: flex; justify-content: space-between; position: relative; margin-bottom: 30px;">
                                <div style="position: absolute; top: 10px; width: 100%; height: 2px; background: #eee; z-index: 1;"></div>
                                <div style="position: absolute; top: 10px; width: 70%; height: 2px; background: #4da3ff; z-index: 1;"></div>
                                <div class="step"><div class="dot active"></div><small>Booked</small></div>
                                <div class="step"><div class="dot active"></div><small>Approved</small></div>
                                <div class="step"><div class="dot active pulse"></div><small>On Way</small></div>
                                <div class="step"><div class="dot"></div><small>Done</small></div>
                            </div>
                            <div class="driver_info" style="border-top: 1px solid #eee; padding-top: 15px; display: flex; align-items: center; gap: 15px;">
                                <div style="width: 40px; height: 40px; background: #f0f7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4da3ff;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div style="flex: 1;">
                                    <h4 style="margin: 0; color: #001d3d;">Driver: Ricardo</h4>
                                    <p style="margin: 0; font-size: 12px; color: #28a745;">Assigned to your order</p>
                                </div>
                                <a href="tel:0912345678" style="background: #28a745; color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                    <i class="fas fa-phone-alt" style="font-size: 14px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                `,
            bookorder:`
                        <div class="booking_container">
                            <div class="booking_header">
                                <h1>Create New Booking</h1>
                                <div class="progress_bar_container" style="display: flex; gap: 10px; margin-bottom: 20px;">
                                    <div id="step_indicator_1" style="height: 5px; flex: 1; background: #4da3ff; border-radius: 5px;"></div>
                                    <div id="step_indicator_2" style="height: 5px; flex: 1; background: #eee; border-radius: 5px;"></div>
                                    <div id="step_indicator_3" style="height: 5px; flex: 1; background: #eee; border-radius: 5px;"></div>
                                    <div id="step_indicator_4" style="height: 5px; flex: 1; background: #eee; border-radius: 5px;"></div>
                                </div>
                            </div>

                            <form id="multiStepForm" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); color: #111;">
                                
                            <div id="phase1">
                                        <h3 style="margin-bottom: 15px;">Delivery Details</h3>
                                        
                                        <label style="font-size: 11px; color: #666;">Service Method</label>
                                        <select id="service_method" onchange="toggleDeliveryFee()" style="width: 100%; padding: 12px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 8px; font-weight: 500;">
                                            <option value="pickup">Driver Pickup & Delivery (₱50.00)</option>
                                            <option value="dropoff">I will drop off & pick up myself (₱0.00)</option>
                                        </select>

                                        <label style="font-size: 11px; color: #666;">Full Name</label>
                                        <input type="text" id="full_name" placeholder="Juan Dela Cruz" style="width: 100%; padding: 12px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 8px;">
                                        
                                        <label style="font-size: 11px; color: #666;">Phone Number</label>
                                        <input type="text" id="phone" placeholder="09123456789" maxlength="11" style="width: 100%; padding: 12px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 8px;">

                                        <div id="address_section">
                                            <label style="font-size: 11px; color: #666;">Area / District</label>
                                            <select id="barangay" style="width: 100%; padding: 12px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 8px;">
                                                <option value="" disabled selected>Select Area...</option>
                                                <option value="Lumbia Proper">Lumbia Proper</option>
                                                <option value="Upper Lumbia">Upper Lumbia</option>
                                                <option value="F. Delima St.">F. Delima St. (Shop Area)</option>
                                                <option value="Gran Europa">Gran Europa</option>
                                                <option value="Pueblo de Oro">Pueblo de Oro Area</option>
                                                <option value="Bayabas/Pagatpat Side">Towards Pagatpat</option>
                                            </select>
                                            
                                            <label style="font-size: 11px; color: #666;">House Details / Street / Landmarks</label>
                                            <input type="text" id="street" placeholder="House No. / Street / Landmarks" style="width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 8px;">
                                        </div>

                                        <button type="button" onclick="goToStep(2)" class="get_started_btn" style="width: 100%; border: none; font-weight: bold; background: #007bff; color: white; padding: 15px; border-radius: 10px; cursor: pointer;">
                                            Next <i class="fas fa-arrow-right"></i>
                                        </button>
                                    </div>
                                <div id="phase2" style="display: none;">
                                    <h3 style="margin-bottom: 15px;">Booking Detail</h3>
                                    <label style="font-size: 12px; color: #666;">Service Type</label>
                                    <select id="service" style="width: 100%; padding: 12px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 8px;">
                                        <option value="" disabled selected>Select Service...</option>
                                        <option value="180">Wash + Dry + Fold — ₱180.00</option>
                                        <option value="150">Wash + Dry — ₱150.00</option>
                                        <option value="65">Wash Only — ₱65.00</option>
                                        <option value="140">Spin + Dry + Fold — ₱140.00</option>
                                        <option value="100">Spin + Dry — ₱100.00</option>
                                        <option value="35">Spin Only — ₱35.00</option>
                                        <option value="250">Power Wash + Dry + Fold — ₱250.00</option>
                                        <option value="220">Power Wash + Dry — ₱220.00</option>
                                        <option value="135">Power Wash Only — ₱135.00</option>
                                    </select>

                                    <label style="font-size: 12px; color: #666;">Detergent</label>
                                    <select id="detergent" style="width: 100%; padding: 12px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 8px;">
                                        <option value="" disabled selected>Select Detergent...</option>
                                        <option value="20">Tide— ₱20.00</option>
                                        <option value="20">Ariel— ₱20.00</option>
                                        <option value="20">Breeze— ₱20.00</option>
                                        <option value="20">Surf— ₱20.00</option>
                                        <option value="0">None (I have my own)</option>
                                    </select>

                                    <label style="font-size: 12px; color: #666;">Fabric Conditioner</label>
                                    <select id="fabcon" style="width: 100%; padding: 12px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 8px;">
                                        <option value="" disabled selected>Select Fabcon...</option>
                                        <option value="15">Downy — ₱15.00</option>
                                        <option value="15">Surf — ₱15.00</option>
                                        <option value="0">None (I have my own)</option>
                                    </select>

                                    <label style="font-size: 12px; color: #666;">Payment Method</label>
                                    <select id="payment_method" style="width: 100%; padding: 12px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 8px;">
                                        <option value="COD">Cash on Delivery (COD)</option>
                                        <option value="GCash">GCash</option>
                                        <option value="Maya">Maya</option>
                                    </select>

                                    <label style="font-size: 12px; color: #666;">Booking Date</label>
                                    <input type="date" id="booking_date" style="width: 100%; padding: 12px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 8px;">

                                    <label style="font-size: 12px; color: #666;">Estimated Load</label>
                                    <select id="loads" style="width: 100%; padding: 12px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 8px;">
                                        <option value="1">Up to 8kg (1 Load)</option>
                                        <option value="2">Up to 16kg (2 Loads)</option>
                                    </select>
                                    
                                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                                        <button type="button" onclick="goToStep(1)" style="flex: 1; padding: 15px; background: #f0f0f0; border: none; border-radius: 10px; font-weight: bold; cursor: pointer;">Back</button>
                                        <button type="button" onclick="goToStep(3)" style="flex: 2; padding: 15px; background: #007bff; color: white; border: none; border-radius: 10px; font-weight: bold; cursor: pointer;">Next</button>
                                    </div>
                                </div>

                                <div id="phase3" style="display: none;">
                                    <h3 style="margin-bottom: 15px;">Review Your Order</h3>
                                    <div id="summary_details" style="background: #f9f9f9; padding: 15px; border-radius: 10px; margin-bottom: 15px; border: 1px solid #eee;"></div>

                                    <div id="receipt_upload_area" style="display: none; background: #fffbe6; padding: 15px; border-radius: 10px; border: 1px solid #ffe58f; margin-bottom: 15px; text-align: center;">
                                        <p style="color: #856404; font-weight: bold; margin-bottom: 5px;">Scan to Pay (Fresh Wave QR)</p>
                                        <img src="image/QRcode.jpeg" alt="GCash QR" style="width: 160px; display: block; margin: 0 auto 10px; border: 2px solid #fff; border-radius: 8px;">
                                        <p style="font-size: 13px; font-weight: bold;">Gcash/Maya: 0904-299-9138</p>
                                        <div style="margin-top: 15px; text-align: left;">
                                            <label style="font-size: 12px; color: #666; font-weight: bold;">Upload Receipt Screenshot:</label>
                                            <input type="file" id="payment_file" accept="image/*" style="width: 100%; margin-top: 5px; font-size: 12px;">
                                        </div>
                                    </div>

                                    <div style="border-top: 1px solid #eee; padding-top: 10px; margin-bottom: 15px;">
                                        <p id="summary_address" style="font-size: 13px; color: #555;"></p>
                                    </div>
                                    
                                    <div style="display: flex; gap: 10px;">
                                        <button type="button" onclick="goToStep(2)" style="flex: 1; padding: 15px; background: #f0f0f0; border: none; border-radius: 10px; font-weight: bold; cursor: pointer;">Back</button>
                                        <button type="button" onclick="placeOrder()" style="flex: 2; padding: 15px; background: #28a745; color: white; border: none; border-radius: 10px; font-weight: bold; cursor: pointer;">Place Order</button>
                                    </div>
                                </div>

                                <div id="phase4" style="display: none; text-align: center;">
                                    <div style="font-size: 60px; color: #28a745; margin-bottom: 10px;"><i class="fas fa-check-circle"></i></div>
                                    <h3 style="margin-bottom: 20px;">Successfully Booked</h3>
                                    <div id="final_receipt_box" style="border: 1px solid #ddd; border-radius: 15px; padding: 20px; text-align: left; margin-bottom: 20px; background: white;"></div>
                                    <button type="button" onclick="loadPage('orderstatus')" style="width: 100%; padding: 15px; background: #001d3d; color: white; border: none; border-radius: 10px; font-weight: bold;">Check Status</button>
                                </div>
                            </form>
                        </div>


                    `,
                myhistory: `<h1>My History</h1><p>Your previous orders.</p>`,
                orderstatus: `<h1>Order Status</h1><p>Your order is currently pending admin approval.</p>`,
                ordersummary: `<h1>Order Summary</h1><p>Your total bill is ₱0.00.</p>`,
                settings: `<h1>Settings</h1><p>Update your profile.</p>`
            };

            

                        function loadPage(pageKey) {
                                        const key = pageKey.toLowerCase().replace(/\s/g, '');
                                        if (pages[key]) {
                                            contentArea.innerHTML = pages[key];

                                            // Handle Nav Active State
                                            navLinks.forEach(link => {
                                                link.classList.remove('active');
                                                const linkText = link.innerText.trim().toLowerCase().replace(/\s/g, '');
                                                if (linkText === key) link.classList.add('active');
                                            });

                                            // Initialize Map if on Track page
                                        if (key === 'trackorder') {
                                                // 1. Get the Area the user selected in Phase 1
                                                const selectedArea = document.getElementById('barangay')?.value || "Lumbia Proper";

                                                // 2. Define "Coordinates" for each area in Lumbia/CDO
                                                const areaCoordinates = {
                                                    "Lumbia Proper": [8.4111, 124.6074],
                                                    "Upper Lumbia": [8.4050, 124.6000],
                                                    "Gran Europa": [8.4230, 124.6200],
                                                    "Pueblo de Oro": [8.4350, 124.6300],
                                                    "F. Delima St.": [8.4120, 124.6080]
                                                };

                                                // 3. Use the selected area or default to Shop center
                                                const shopLocation = [8.4120, 124.6080]; // Your Shop Location
                                                const customerLocation = areaCoordinates[selectedArea] || [8.4150, 124.6100];

                                                setTimeout(() => {
                                                    if (mapInstance) mapInstance.remove();
                                                    
                                                    mapInstance = L.map('map').setView(shopLocation, 14);
                                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapInstance);

                                                    // This draws the route based on the DROPDOWN choice
                                                    L.Routing.control({
                                                        waypoints: [
                                                            L.latLng(shopLocation),
                                                            L.latLng(customerLocation)
                                                        ],
                                                        lineOptions: { styles: [{ color: '#4da3ff', weight: 6 }] },
                                                        show: false
                                                    }).addTo(mapInstance);
                                                }, 200);
                                            }
                                        }
                                    }
                                        // --- PLACE THIS INSIDE YOUR <SCRIPT> TAG, AFTER loadPage() ---

                            // 1. FIX: Toggle Address Visibility
                                            function toggleDeliveryFee() {
                        const method = document.getElementById('service_method').value;
                        const addressSection = document.getElementById('address_section');
                        if (addressSection) {
                            // If "dropoff", hide address. If "pickup", show address.
                            addressSection.style.display = (method === 'dropoff') ? 'none' : 'block';
                        }
                    }

                // 2. FIX: Navigation Logic (Next/Back Buttons)
                function goToStep(step) {
                    // Validation: Phase 1 -> Phase 2
                    if (step === 2) {
                        const name = document.getElementById('full_name').value;
                        const phone = document.getElementById('phone').value;
                        const method = document.getElementById('service_method').value;

                        if (!name || !phone) {
                            alert("Please enter your Name and Phone Number.");
                            return;
                        }

                        // Only validate address if Driver Pickup is selected
                        if (method === 'pickup') {
                            const brgy = document.getElementById('barangay').value;
                            const street = document.getElementById('street').value;
                            if (!brgy || !street) {
                                alert("Please provide your Area and Street for pickup.");
                                return;
                            }
                        }
                    }

                    // Validation: Phase 2 -> Phase 3
                    if (step === 3) {
                        const service = document.getElementById('service').value;
                        const date = document.getElementById('booking_date').value;
                        if (!service || !date) {
                            alert("Please select a Service and Booking Date.");
                            return;
                        }
                        calculateSummary(); // Updates the Review screen before showing it
                    }

                    // Actual Phase Switching Logic
                    const phases = ['phase1', 'phase2', 'phase3', 'phase4'];
                    phases.forEach((id, index) => {
                        const element = document.getElementById(id);
                        if (element) {
                            element.style.display = (index + 1 === step) ? 'block' : 'none';
                        }
                    });

                    // Update the Progress Bar Indicators
                    for (let i = 1; i <= 4; i++) {
                        const indicator = document.getElementById('step_indicator_' + i);
                        if (indicator) {
                            indicator.style.background = (i <= step) ? '#4da3ff' : '#eee';
                        }
                    }
                }

                function calculateSummary() {
                    // 1. Capture Inputs
                    const name = document.getElementById('full_name').value;
                    const phone = document.getElementById('phone').value;
                    const paymentMethod = document.getElementById('payment_method').value;
                    const date = document.getElementById('booking_date').value || new Date().toISOString().split('T')[0];

                    // 2. Get Selected Service Price
                    const serviceSelect = document.getElementById('service');
                    const servicePrice = parseFloat(serviceSelect.value) || 0;
                    const serviceName = serviceSelect.options[serviceSelect.selectedIndex].text.split('—')[0];

                    // 3. Get Detergent & Fabcon Prices
                    const detergentSelect = document.getElementById('detergent');
                    const detergentPrice = parseFloat(detergentSelect.value) || 0;
                    const detergentName = detergentSelect.options[detergentSelect.selectedIndex].text.split('—')[0];

                    const fabconSelect = document.getElementById('fabcon');
                    const fabconPrice = parseFloat(fabconSelect.value) || 0;
                    const fabconName = fabconSelect.options[fabconSelect.selectedIndex].text.split('—')[0];

                    // 4. Delivery Logic
                    const addressSection = document.getElementById('address_section');
                    const method = document.getElementById('service_method').value;
                    let deliveryTo = "Customer Drop-off";
                    let deliveryFee = 0;

                    // Logic for notice text
                    let weightNoticeText = "";
                    if (method === 'pickup') {
                        const area = document.getElementById('barangay').value;
                        const street = document.getElementById('street').value;
                        deliveryTo = `${street}, ${area}`;
                        deliveryFee = 50.00;
                        weightNoticeText = "* <strong>Important:</strong> Our driver will conduct the <strong>final weighing</strong> upon collection. Please note that the total amount may be adjusted based on the actual weight of your laundry.";
                    } else {
                        weightNoticeText = "* <strong>Important:</strong> The <strong>final weighing</strong> and price verification will be completed by our shop staff once you drop off your items. The current total is an estimate.";
                    }

                    // 5. Calculate Total
                    const subtotal = servicePrice + detergentPrice + fabconPrice + deliveryFee;

                    // 6. Build the Detailed Table Summary
                    const summaryDiv = document.getElementById('summary_details');
                    if (summaryDiv) {
                        summaryDiv.innerHTML = `
                            <div style="background: #fdfdfd; padding: 15px; border-radius: 8px; border: 1px solid #eee; font-family: sans-serif;">
                                <table style="width: 100%; border-collapse: collapse; font-size: 0.95em;">
                                    <tr><td style="padding: 5px 0; color: #555;">${serviceName}</td><td style="text-align: right; font-weight: 600;">₱${servicePrice.toFixed(2)}</td></tr>
                                    <tr><td style="padding: 5px 0; color: #555;">${detergentName}</td><td style="text-align: right; font-weight: 600;">₱${detergentPrice.toFixed(2)}</td></tr>
                                    <tr><td style="padding: 5px 0; color: #555;">${fabconName}</td><td style="text-align: right; font-weight: 600;">₱${fabconPrice.toFixed(2)}</td></tr>
                                    <tr><td style="padding: 5px 0; color: #555;">Delivery Fee</td><td style="text-align: right; font-weight: 600;">₱${deliveryFee.toFixed(2)}</td></tr>
                                    <tr><td style="padding: 5px 0; color: #555;">Booking Date</td><td style="text-align: right; font-weight: 600;">${date}</td></tr>
                                    <tr><td style="padding: 5px 0; color: #555;">Payment Method</td><td style="text-align: right; font-weight: 600;">${paymentMethod}</td></tr>
                                    
                                    <tr style="border-top: 2px solid #4da3ff; font-weight: bold; font-size: 1.1em;">
                                        <td style="padding: 10px 0;">Subtotal Amount</td>
                                        <td style="text-align: right; padding: 10px 0; color: #007bff;">₱${subtotal.toFixed(2)}</td>
                                    </tr>
                                </table>

                                <p style="margin-top: 15px; font-size: 0.85em; color: #2c2b2b; font-style: italic; border-top: 1px dashed #ccc; padding-top: 10px; line-height: 1.4;">
                                    ${weightNoticeText}
                                </p>
                                
                                <p style="margin-top: 10px; font-size: 0.9em; color: #555; background: #f0f7ff; padding: 8px; border-radius: 5px;">
                                    <strong>Delivery to:</strong><br>${deliveryTo}
                                </p>
                            </div>
                        `;
                    }

                    // 7. QR Code Logic
                    const receiptArea = document.getElementById('receipt_upload_area');
                    if (receiptArea) {
                        receiptArea.style.display = (paymentMethod === 'GCash' || paymentMethod === 'Maya') ? 'block' : 'none';
                    }
                }

                function placeOrder() {
                const payment = document.getElementById('payment_method').value;
                const fileInput = document.getElementById('payment_file');

                if (payment !== 'COD' && (!fileInput.files || !fileInput.files[0])) {
                    alert("Please upload your payment receipt screenshot before placing the order.");
                    return;
                }

                // --- NEW: CAPTURE DATA BEFORE MOVING TO PHASE 4 ---
                const serviceSelect = document.getElementById('service');
                const selectedText = serviceSelect.options[serviceSelect.selectedIndex].text;
                
                // Create the order object
                const newOrder = {
                    id: "FW-" + Math.floor(1000 + Math.random() * 9000),
                    service: selectedText.split('—')[0], // Just gets "Wash + Dry + Fold"
                    total: document.querySelector('#summary_details td[style*="color: #007bff;"]')?.innerText || "₱0.00",
                    date: document.getElementById('booking_date').value || "Today",
                    status: "Pending Approval"
                };

                // Push it into the let userOrders = [] list at the top of your script
                userOrders.push(newOrder); 
                // -------------------------------------------------

                // Proceed to Phase 4 (Success)
                goToStep(4);

                // Copy summary to the final success box
                const finalBox = document.getElementById('final_receipt_box');
                const summary = document.getElementById('summary_details');
                if (finalBox && summary) {
                    finalBox.innerHTML = summary.innerHTML;
                }
            }
                                                                                                            
             const navLinks = document.querySelectorAll('nav a');

                navLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        if (this.classList.contains('logout')) return;
                        e.preventDefault();
                        navLinks.forEach(l => l.classList.remove('active'));
                        this.classList.add('active');
                        loadPage(this.innerText.trim());
                    });
                });

                // Default Page
                loadPage('overview');
</script>
</body>
</html>