<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MySalonHair&Beauty | @yield('title')</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

   <link rel="stylesheet" href="{{ asset('assets/css/appointment.css') }}">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    @yield('css')
    <style>
body{
    margin: 0;
    padding: 0;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    background-color: #fef7f7;
    overflow-x:hidden;
}

/* Login */
.main-login {
  margin: 0;
  font-family: Arial, sans-serif;
  background-color: #f3f5ff;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.login-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
}

.login-box {
  background: #fff;
  padding: 40px 50px;
  border-radius: 6px;
  width: 400px;
  height: 520px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
  text-align: center;
}

.logo {
  width: 120px;
  margin-bottom: 20px;
}

.login-title {
  font-size: 20px;
  font-weight: 600;
  margin-bottom: 5px;
  color: #222;
}

.subtitle {
  font-size: 14px;
  margin-bottom: 30px;
  color: #444;
}

.inputbox {
  width: 90%;
  padding: 14px;
  margin-bottom: 18px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 15px;
  color: #333;
}

.sign-in-button {
  width: 100%;
  padding: 14px;
  background-color: #4b45c5;
  color: #fff;
  border: none;
  border-radius: 10px;
  font-size: 16px;
  font-weight: bold;
  margin-top: 5px;
  cursor: pointer;
}

.sign-in-button a {
  color: #fff;
  text-decoration: none;
}

.sign-in-button:hover {
  background-color: #3e39a3;
}

.options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 18px;
  font-size: 13px;
}

.options input[type="checkbox"] {
  margin-right: 6px;
}

.forgot {
  color: #000;
  text-decoration: none;
}

.forgot:hover {
  text-decoration: underline;
}

/* Login End */

/* .wrapper {
  display: flex;
  height: 100vh;
} */

/* LeftsideBar */
.sidebar {
  width: 200px;
  background-color: #f5a700;
  min-height: 100vh;
}
.sidebar-logo {
  background-color: #fff;
  padding: 10px;
  margin: 10px auto;
  border-radius: 50%;
  width: 120px;
  height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.sidebar-logo img.logo-img {
  max-width: 120%;
  max-height: 120%;
  object-fit: cover;
}

.sidebar-buttons {
  gap: 10px;
}

/* Card style buttons in sidebar */
.sidebar-card {
  background-color: white;
  color: #f5a700;
  border-radius: 6px;
  padding: 10px 5px;
  width: 150px;
  height: 100px;
  margin: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-decoration: none;
}

.sidebar-card:hover {
  background-color: #586863;
  color: #fff;
}
.sidebar-card i.fas {
  font-size: 2rem !important;
  margin-bottom: 5px;
  color: #f5a700;
}
.sidebar-card:hover i.bi {
  color: #fff;
}

.sidebar-card .label {
  font-size: 1rem;
  font-weight: 700;
  margin-top: 4px;
  line-height: 1.2;
  text-align: center;
}
.sidebar-card.active {
  background-color: #fff3cd;
  box-shadow: inset 0 0 0 2px #f5a700;
}

.footer {
  height: 35px;
  background-color: #586863;
  color: white;
  font-size: 16px;
  display: flex;
  align-items: center;
  padding: 0 10px;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  position: fixed;
  bottom: 0;
  left: 13%;
  right: 0;
  z-index: 999;
}

footer .logout {
  cursor: pointer;
}

/* Responsive */
@media (max-width: 768px) {
  .d-flex.flex-md-row {
    flex-direction: column !important;
  }

  .sidebar {
    width: 100%;
    min-height: auto;
  }

  .sidebar-card {
    width: 30%;
    min-width: 90px;
    flex: 1 1 auto;
  }

  .footer {
    flex-direction: column;
    height: auto;
    text-align: center;
    gap: 5px;
    padding: 6px;
  }

  .footer span,
  .footer i {
    width: 100%;
  }
}
/* LeftsideBar End */

.rightbar {
  height: 100vh;
  background-color: #343a40;
  color: white;
  padding: 10px;
  overflow-y: auto;
}

.section-title {
  font-weight: bold;
  margin-top: 10px;
}

.form-control-sm,
.btn-sm {
  font-size: 0.875rem;
}

.chair-row {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.chair-card {
  width: 120px;
  margin-bottom: 10px;
  background-color: #198754;
  color: white;
  text-align: center;
  padding: 12px;
  margin: 5px;

  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.chair-card.occupied {
  background-color: #ffc107 !important;
}

.chair-card.expired {
  background-color: #ffc107 !important;
  animation: blink 1s infinite;
  color: black !important;
}

@keyframes blink {
  0%,
  100% {
    opacity: 1;
  }

  50% {
    opacity: 0.5;
  }
}

.countdown-timer {
  font-size: 12px;
  margin-top: 5px;
}

.chair-icon {
  font-size: 1rem;
  margin-bottom: 6px;
  display: block;
}

/*.services-row {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
    }*/

/* .services-row { */
/* display: grid; */
/* grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); */
/* /* gap: 12px; */
/* flex-wrap: wrap; */
/* align-items: start;
  padding-bottom: 10px; */
/* 
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
} */

/* .service-card {
  width: 180px;
  padding: 12px;
  border-radius: 10px;
  text-align: center;
  display: flex;
  align-items: center;
  gap: 10px;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
  transition: transform 0.2s ease, background 0.3s ease;
  cursor: pointer;
  font-size: 0.95rem;
  font-weight: 500;
  margin: 5px;
  background-color: #1f1f1f;
  color: #f1f1f1;
} */

/* .service-card {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 500;
        background-color: #1f1f1f;
        color: #f1f1f1;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s ease, background 0.3s ease;
        cursor: pointer;


        width: auto !important;
        display: flex !important;
    } */
/* 
.service-card:hover {
  transform: scale(1.02);
  filter: brightness(1.1);
} */

/* .service-icon {
  width: 28px;
  height: 28px; */
/* margin-right: 8px; */
/* flex-shrink: 0;
} */

/* Small screens */
/* @media (max-width: 600px) {
  .services-row {
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
  }

  .service-card {
    font-size: 0.85rem;
    padding: 10px;
  }

  .service-icon {
    width: 24px;
    height: 24px;
  }
} */

/* @media (min-width: 1024px) {
  .service-card {
    font-size: 1rem;
    padding: 16px;
  }

  .service-icon {
    width: 30px;
    height: 30px;
  }
} */

#orderStatus {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 10px;
  font-size: 16px;
  font-weight: 500;
}

.status-cards {
  width: 125px;
  background: #fff;
  color: #000;
  padding: 10px;
  border-radius: 10px;
  text-align: center;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

#serviceList tbody {
  display: block;
  max-height: 30vh;
  overflow-y: auto;
}

#servicesContainer {
  flex: 1 1 auto;
  overflow-y: auto;
  padding-right: 5px;
  margin-bottom: 10px;
}

#orderStatus,
#chairContainer {
  flex-shrink: 0;
}

/* DashBoard */

.status-card {
  min-width: 300px;
  padding: 16px;
  text-align: center;
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  font-weight: 600;
  font-size: 24px;
}

.status-card .fs-4 {
  font-weight: 600;
}

/* ********* Branch *********** */
/* *************************** */

.branch-table th {
  background-color: #333;
  color: #fff;
}

.branch-table td,
.branch-table th {
  vertical-align: middle;
}

.wrapper {
  display: flex;
  height: 100vh;
  overflow: hidden;
}

.main-contentt {
  height: 100vh;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

#branchSection {
  flex-grow: 1;
  overflow-y: auto;
}

/* ********* Dashboard *********** */
/* ****************************** */

/* .main-contentt {
  height: 100vh;
  background-color: #f8f9fa;
  padding: 10px;
  overflow-y: auto;
} */

.status-card {
  min-width: 300px;
  padding: 16px;
  text-align: center;
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  font-weight: 600;
  font-size: 24px;
}
*/ .status-card .fs-4 {
  font-weight: 600;
}

/* Manage */
.status-cardM {
  min-width: 600px;
  min-height: 250px;
  padding: 50px;
  text-align: center;
  font-size: 26px;
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  font-weight: 600;

  display: flex;
  justify-content: center;
  align-items: center;
}

.status-cardM .fs-4 {
  font-weight: 600;
}
.btn-colors-a a {
  text-decoration: none;
  color: #fff;
}

/* ********* Product *********** */
/* **************************** */
.modal-header {
  background-color: #f6ad00;
}

.modal-footer .btn {
  min-width: 80px;
}

.product-table th {
  background-color: #333;
  color: #fff;
}

.product-table td,
.product-table th {
  vertical-align: middle;
}

#productSection {
  flex-grow: 1;
  overflow-y: auto;
}

/* ********* Service *********** */
/* **************************** */

.service-table th {
  background-color: #333;
  color: #fff;
}

.service-table td,
.service-table th {
  vertical-align: middle;
}

.wrapper {
  display: flex;
  height: 100vh;
  overflow: hidden;
}

.main-contentt {
  overflow: hidden;
  height: 100vh;
  display: flex;
  flex-direction: column;
}

#staffSection {
  flex-grow: 1;
  overflow-y: auto;
  padding-bottom: 1rem;
}

/* ********* Staff *********** */
/* **************************** */

.staff-table th {
  background-color: #333;
  color: #fff;
}

.staff-table td,
.staff-table th {
  vertical-align: middle;
}

.wrapper {
  display: flex;
  height: 100vh;
  overflow: hidden;
}

.main-contentt {
  flex-grow: 1;
  background-color: #f8f9fa;
  padding: 10px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

    </style>

</head>

<body>
    <div class="container-fluid h-100">
        <div class="row flex-nowrap h-100">
