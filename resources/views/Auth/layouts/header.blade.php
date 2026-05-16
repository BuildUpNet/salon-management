<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Smart-Invoicer Login</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    />

    <style>
      body {
        background: #000; 
        color: #ffc107; 
        display: flex;
        flex-direction: column;
        margin: 0;
        min-height: 100vh;
		 overflow: hidden;
      }
	 

.main-wrapper {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 30px 15px;
  padding-bottom: 60px; 
  overflow: auto; 
}

      .login-container {
        background: #fff; /* white container */
        border-radius: 10px;
        overflow: hidden;
        display: flex;
        flex-wrap: wrap;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
      }

      .left-panel,
      .right-panel {
        width: 50%;
        box-sizing: border-box;
      }

      .left-panel {
        background-color: #000; /* black */
        color: #ffc107; /* yellow text */
        padding: 40px 30px;
      }
      .left-panel h2 {
        font-weight: 700;
        color: #ffc107;
      }

      .feature-boxes {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-top: 30px;
      }

      .feature-box {
        background-color: #ffc107; /* yellow */
        padding: 15px;
        border-radius: 10px;
        color: #000; /* black text */
        text-align: center;
      }

    

      .feature-box i {
        font-size: 24px;
        margin-bottom: 8px;
        display: block;
        color: #000; /* icon color black for contrast */
      }

      .right-panel {
        background-color: #fff; /* white background */
        padding: 40px 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #000; /* black text */
      }

      .login-form {
        width: 90%;
      }

      .login-form h4 {
        margin-bottom: 25px;
        font-weight: 700;
        color: #000;
      }

      .form-label strong {
        color: #000;
      }

      .form-control {
        margin-bottom: 15px;
        border: 1px solid #ffc107; /* yellow border */
      }
      .form-control:focus {
        border-color: #000;
        box-shadow: 0 0 5px #ffc107;
      }
      .login-btn {
        background-color: #ffc107; /* yellow button */
        color: #000; /* black text */
        border: none;
        width: 50%;
        margin: 0 auto;
        display: block;
        font-weight: 600;
        transition: all 0.3s ease;
        border-radius: 5px;
      }

      .login-btn:hover {
        background-color: #000; /* black background on hover */
        color: #fff; /* white text */
        cursor: pointer;
      }

       footer {
  background-color: #000;
  color: #ffc107;
  text-align: center;
  padding: 12px 0;
  font-size: 13px;
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
}

      footer img {
        height: 26px;
        margin-left: 6px;
        vertical-align: middle;
      }

      @media (max-width: 992px) {
        .feature-boxes {
          grid-template-columns: repeat(2, 1fr);
        }
      }

      @media (max-width: 768px) {
        .login-container {
          flex-direction: column;
        }

        .left-panel,
        .right-panel {
          width: 100%;
        }

        .feature-boxes {
          grid-template-columns: 1fr;
        }
      }
    </style>
  </head>
  <body>
    <!-- Main Page Body -->
    <div class="main-wrapper">
      <div class="login-container">
 <!-- Left Panel -->
        <div class="left-panel">
          <h2>Welcome to SalonPOS</h2>
          <p class="mt-2">
            Run your salon more efficiently with our all-in-one POS solution.
            From quick billing and real-time appointment tracking to customer
            history and reporting — SalonPOS is designed to save you time and
            grow your business.
          </p>

          <div class="feature-boxes">
            <div class="feature-box">
              <!-- <i class="bi bi-cash-register"></i> -->
              <i class="bi bi-credit-card"></i>

              <strong>Fast Billing</strong>
              <p class="mb-0">
                Generate itemized bills with taxes, discounts, and stylist info
                in just a few taps.
              </p>
            </div>
            <div class="feature-box">
              <i class="bi bi-calendar-check"></i>
              <strong>Appointment Booking</strong>
              <p class="mb-0">
                Schedule, reschedule, and track appointments with ease for
                walk-ins or regular clients.
              </p>
            </div>
            <div class="feature-box">
              <i class="bi bi-person-lines-fill"></i>
              <strong>Client Profiles</strong>
              <p class="mb-0">
                Maintain detailed customer history including past services,
                preferences, and notes.
              </p>
            </div>
            <div class="feature-box">
              <i class="bi bi-graph-up"></i>
              <strong>Business Reports</strong>
              <p class="mb-0">
                Track daily earnings, staff performance, and service trends with
                insightful dashboards.
              </p>
            </div>
            <div class="feature-box">
              <i class="bi bi-lock-fill"></i>
              <strong>Secure Access</strong>
              <p class="mb-0">
                Only authorized users can access system features through secure
                login roles.
              </p>
            </div>
            <div class="feature-box">
              <i class="bi bi-gear"></i>
              <strong>Customizable Services</strong>
              <p class="mb-0">
                Add, edit, and manage services, stylists, and pricing according
                to your salon’s needs.
              </p>
            </div>
          </div>
        </div>
