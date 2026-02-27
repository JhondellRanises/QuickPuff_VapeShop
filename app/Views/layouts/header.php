<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) : 'QuickPuff VapeShop' ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-blue: #4A90E2;
            --primary-purple: #7B68EE;
            --accent-color: #00d4ff;
            --dark-bg: #0A0E27;
            --darker-bg: #060818;
            --card-bg: rgba(255, 255, 255, 0.05);
            --text-primary: #FFFFFF;
            --text-secondary: #E4E6EB;
            --text-muted: #B0B3B8;
            --text-dim: #8A8D91;
            --border-color: rgba(255, 255, 255, 0.15);
            --success-color: #00D68F;
            --warning-color: #FFB400;
            --error-color: #FF4757;
            --info-color: #4A90E2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--darker-bg) 0%, var(--dark-bg) 100%);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-weight: 400;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Ensure all text elements have proper colors */
        h1, h2, h3, h4, h5, h6 {
            color: var(--text-primary) !important;
        }

        p {
            color: var(--text-primary);
        }

        strong, b {
            color: var(--text-primary);
        }

        /* Enhanced text colors for better readability */
        .text-primary {
            color: var(--primary-purple) !important;
        }

        .text-secondary {
            color: var(--text-secondary) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        .text-success {
            color: var(--success-color) !important;
        }

        .text-danger {
            color: var(--error-color) !important;
        }

        .text-warning {
            color: var(--warning-color) !important;
        }

        .text-info {
            color: var(--info-color) !important;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--darker-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-purple);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-blue);
        }

        .navbar {
            background: rgba(10, 14, 39, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            padding: 0.5rem 0;
            min-height: auto;
        }

        .navbar-brand {
            color: var(--text-primary) !important;
            font-weight: 700;
            font-size: 2rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            color: var(--accent-color) !important;
            transform: translateY(-1px);
        }

        .navbar-brand i {
            font-size: 1.8rem;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-purple) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-nav .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin: 0 0.25rem;
        }

        .navbar-nav .nav-link:hover {
            color: var(--text-primary) !important;
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-1px);
        }

        .navbar-nav .nav-link i {
            margin-right: 0.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-purple) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.95rem;
            margin: 0;
        }

        .user-role {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(0, 214, 143, 0.1);
            border: 1px solid rgba(0, 214, 143, 0.2);
            color: var(--success-color);
        }

        .alert-danger {
            background: rgba(255, 71, 87, 0.1);
            border: 1px solid rgba(255, 71, 87, 0.2);
            color: var(--error-color);
        }

        .alert-warning {
            background: rgba(255, 180, 0, 0.1);
            border: 1px solid rgba(255, 180, 0, 0.2);
            color: var(--warning-color);
        }

        .alert-info {
            background: rgba(74, 144, 226, 0.1);
            border: 1px solid rgba(74, 144, 226, 0.2);
            color: var(--info-color);
        }

        /* Button Styles */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.625rem 1.25rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-purple) 100%);
            color: var(--text-primary);
            box-shadow: 0 4px 15px rgba(123, 104, 238, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(123, 104, 238, 0.4);
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-purple) 100%);
        }

        .btn-outline-primary {
            background: transparent;
            border: 1px solid var(--primary-purple);
            color: var(--primary-purple);
        }

        .btn-outline-primary:hover {
            background: var(--primary-purple);
            color: var(--text-primary);
            transform: translateY(-1px);
        }

        .btn-danger {
            background: var(--error-color);
            color: var(--text-primary);
        }

        .btn-danger:hover {
            background: #FF6B7A;
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--success-color);
            color: var(--text-primary);
        }

        .btn-success:hover {
            background: #26E5A1;
            transform: translateY(-1px);
        }

        /* Card Styles - Enhanced dark theme */
        .card {
            background: var(--card-bg) !important;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            backdrop-filter: blur(20px);
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(123, 104, 238, 0.15);
            border-color: rgba(123, 104, 238, 0.3);
        }

        .card-header {
            background: rgba(255, 255, 255, 0.02) !important;
            border-bottom: 1px solid var(--border-color);
            border-radius: 16px 16px 0 0 !important;
            padding: 1.25rem;
        }

        .card-body {
            background: transparent !important;
            padding: 1.5rem;
        }

        .card-title {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .card-text {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .card .text-muted {
            color: var(--text-muted) !important;
        }

        .card small {
            color: var(--text-muted) !important;
        }

        .card .badge {
            font-weight: 500;
        }

        /* Remove all white backgrounds from card elements */
        .card * {
            background-color: transparent !important;
        }

        /* Ensure card header and body maintain dark backgrounds */
        .card-header,
        .card-body {
            background: var(--card-bg) !important;
        }

        /* Override any Bootstrap card background overrides */
        .card,
        .card .card,
        .card-body .card,
        .card-header .card,
        .card-footer .card {
            background: var(--card-bg) !important;
        }

        /* Force dark background on all card containers */
        .container-fluid .card,
        .main-content .card,
        .card .card-body,
        .card .card-header {
            background: var(--card-bg) !important;
        }

        /* Table Styles - Softer colors for user lists */
        .table {
            background: var(--card-bg) !important;
            color: var(--text-secondary);
            border-radius: 12px;
            overflow: hidden;
        }

        .table th {
            background: rgba(255, 255, 255, 0.06);
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem;
        }

        .table td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            padding: 1rem;
            vertical-align: middle;
            color: var(--text-secondary);
            background: transparent !important;
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.04) !important;
        }

        .table .text-muted {
            color: var(--text-dim) !important;
        }

        .table small {
            color: var(--text-dim) !important;
        }

        /* Remove white background from all table elements */
        .table * {
            background-color: transparent !important;
        }

        /* Override Bootstrap default table styles */
        .table-striped > tbody > tr:nth-of-type(odd) > td,
        .table-striped > tbody > tr:nth-of-type(odd) > th {
            background-color: transparent !important;
        }

        .table-hover > tbody > tr:hover > td,
        .table-hover > tbody > tr:hover > th {
            background-color: rgba(255, 255, 255, 0.04) !important;
        }

        /* User avatar text */
        .user-avatar {
            color: #FFFFFF !important;
        }

        /* Username and names - slightly softer */
        .table td:first-child,
        .table td:nth-child(2),
        .table td:nth-child(3) {
            color: #E4E6EB !important;
        }

        /* Role badges - keep original colors */
        .badge {
            font-weight: 500;
        }

        /* Status badges - enhanced colors */
        .status-active {
            background: rgba(0, 214, 143, 0.15);
            color: #00D68F !important;
            border: 1px solid rgba(0, 214, 143, 0.25);
        }

        .status-inactive {
            background: rgba(255, 71, 87, 0.15);
            color: #FF6B7A !important;
            border: 1px solid rgba(255, 71, 87, 0.25);
        }

        /* Form Styles - Enhanced dark theme */
        .form-control {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 0.25rem rgba(123, 104, 238, 0.25);
            color: var(--text-primary);
        }

        .form-control::placeholder {
            color: var(--text-dim) !important;
            opacity: 0.8;
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        /* Select Dropdown Styles - Dark theme */
        select.form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid var(--border-color);
            color: var(--text-primary) !important;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23B0B3B8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 16px 12px !important;
            padding-right: 2.5rem !important;
        }

        select.form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 0.25rem rgba(123, 104, 238, 0.25);
            color: var(--text-primary) !important;
        }

        /* Dropdown options */
        select.form-control option,
        .form-select option {
            background: var(--dark-bg) !important;
            color: var(--text-primary) !important;
            padding: 0.5rem;
        }

        /* POS Product Cards - Original Design Enhanced */
        .product-card {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-radius: 12px !important;
            transition: all 0.3s ease !important;
            height: 100% !important;
            overflow: hidden !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .product-card:hover {
            background: rgba(255, 255, 255, 0.12) !important;
        }

.product-card .card-footer {
            background: rgba(255, 255, 255, 0.02) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
            padding: 1rem !important;
            margin: 0 !important;
        }

        .product-card .card-title {
            color: #FFFFFF !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            margin-bottom: 0.75rem !important;
            line-height: 1.3 !important;
        }

        .product-card .card-text {
            color: #FFFFFF !important;
            font-size: 0.85rem !important;
            margin-bottom: 0 !important;
            line-height: 1.4 !important;
        }

        .product-card .text-muted {
            color: #B0B3B8 !important;
            font-size: 0.8rem !important;
        }

        .product-card strong {
            color: #FFFFFF !important;
            font-weight: 600 !important;
        }

        .product-card .badge {
            font-size: 0.7rem !important;
            padding: 0.25rem 0.5rem !important;
            margin-left: 0.5rem !important;
        }

        /* Product item container */
        .product-item {
            margin-bottom: 1rem !important;
        }

        /* Products container */
        #productsContainer {
            padding: 0.5rem !important;
        }

        /* Product grid adjustments */
        .product-item .col-md-6,
        .product-item .col-lg-4 {
            padding: 0.5rem !important;
        }

        /* Fix button visibility in product cards */
        .product-card .btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-weight: 500 !important;
            text-transform: none !important;
            white-space: nowrap !important;
        }

        .product-card .btn:disabled {
            opacity: 0.6 !important;
            cursor: not-allowed !important;
        }

        .product-card .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-purple) 100%) !important;
            border: none !important;
            color: white !important;
        }

        .product-card .btn-primary:hover:not(:disabled) {
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 15px rgba(123, 104, 238, 0.3) !important;
        }

        /* Fix table styling in all contexts */
        .table {
            background: var(--card-bg) !important;
            color: var(--text-secondary) !important;
            border-radius: 12px !important;
            overflow: hidden !important;
            border: 1px solid var(--border-color) !important;
        }

        .table th {
            background: rgba(255, 255, 255, 0.06) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
            font-weight: 600 !important;
        }

        .table td {
            background: transparent !important;
            color: var(--text-secondary) !important;
            border-color: var(--border-color) !important;
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.03) !important;
        }

        /* Remove all white backgrounds from cards and containers */
        .card,
        .card-header,
        .card-body,
        .card-footer {
            background: var(--card-bg) !important;
            border-color: var(--border-color) !important;
        }

        /* Fix any remaining white backgrounds */
        * {
            background-color: transparent !important;
        }

        /* Restore proper backgrounds for specific elements */
        body,
        .navbar,
        .main-content,
        .card,
        .card-header,
        .card-body,
        .card-footer,
        .product-card,
        .table,
        .modal-content {
            background-color: var(--card-bg) !important;
        }

        /* Stats Cards */
        .stats-card {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            border-radius: 12px !important;
            transition: all 0.3s ease !important;
        }

        .stats-card:hover {
            background: rgba(255, 255, 255, 0.12) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(123, 104, 238, 0.2) !important;
        }

        .stats-card .card-body {
            background: transparent !important;
            text-align: center !important;
            padding: 1.5rem !important;
        }

        .stats-value {
            font-size: 2rem !important;
            font-weight: 700 !important;
            color: var(--text-primary) !important;
            margin-bottom: 0.5rem !important;
        }

        .stats-label {
            color: var(--text-secondary) !important;
            font-size: 0.9rem !important;
            font-weight: 500 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        /* Remove white background from all form elements */
        .form-control,
        .form-select,
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="tel"],
        input[type="search"],
        input[type="username"],
        textarea,
        select {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: var(--text-primary) !important;
        }

        /* Force dark background on all input types */
        input {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: var(--text-primary) !important;
        }

        /* Override any remaining white backgrounds */
        * input[type="text"],
        * input[type="email"],
        * input[type="password"],
        * input[type="number"],
        * input[type="tel"],
        * input[type="search"],
        * input[type="username"],
        * textarea,
        * select {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        /* Login form specific fixes */
        .login-form input,
        .auth-form input,
        .form-control:focus,
        input:focus {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: var(--text-primary) !important;
        }

        /* Aggressive override for any stubborn white backgrounds */
        * {
            background-color: transparent !important;
        }

        /* Restore proper backgrounds for specific elements */
        body,
        .card,
        .table,
        .navbar,
        .form-control,
        .form-select,
        input,
        select,
        textarea,
        .dropdown-menu,
        .modal-content,
        .product-card {
            background-color: var(--card-bg) !important;
        }

        /* Modal Styles - Less Transparent */
        .modal-content {
            background: rgba(10, 14, 39, 0.95) !important;
            border: 1px solid var(--border-color) !important;
        }
        
        .modal-header {
            background: rgba(10, 14, 39, 0.95) !important;
            border-bottom: 1px solid var(--border-color) !important;
        }
        
        .modal-body {
            background: rgba(10, 14, 39, 0.95) !important;
        }
        
        .modal-footer {
            background: rgba(10, 14, 39, 0.95) !important;
            border-top: 1px solid var(--border-color) !important;
        }

        /* Force dark backgrounds on all inputs */
        input,
        input.form-control,
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="tel"],
        input[type="search"],
        input[type="username"],
        textarea,
        select,
        .form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.05) !important;
            color: var(--text-primary) !important;
            border: 1px solid var(--border-color) !important;
        }

        /* Focus states for all inputs */
        input:focus,
        input.form-control:focus,
        textarea:focus,
        select:focus,
        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.08) !important;
            color: var(--text-primary) !important;
            border-color: var(--primary-purple) !important;
        }

        /* Form input groups */
        .input-group .form-control {
            background: rgba(255, 255, 255, 0.05) !important;
            color: var(--text-primary) !important;
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid var(--border-color);
            color: var(--text-secondary) !important;
        }

        /* Badge Styles - Softer colors */
        .badge {
            font-weight: 500;
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
        }

        .bg-success {
            background-color: rgba(0, 214, 143, 0.2) !important;
            color: #00D68F !important;
            border: 1px solid rgba(0, 214, 143, 0.3);
        }

        .bg-danger {
            background-color: rgba(255, 71, 87, 0.2) !important;
            color: #FF6B7A !important;
            border: 1px solid rgba(255, 71, 87, 0.3);
        }

        .bg-warning {
            background-color: rgba(255, 180, 0, 0.2) !important;
            color: #FFC93D !important;
            border: 1px solid rgba(255, 180, 0, 0.3);
        }

        .bg-info {
            background-color: rgba(74, 144, 226, 0.2) !important;
            color: #4A90E2 !important;
            border: 1px solid rgba(74, 144, 226, 0.3);
        }

        /* Text Colors */
        .text-primary {
            color: var(--primary-purple) !important;
        }

        .text-secondary {
            color: var(--text-secondary) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        .text-success {
            color: var(--success-color) !important;
        }

        .text-danger {
            color: var(--error-color) !important;
        }

        .text-warning {
            color: var(--warning-color) !important;
        }

        .text-info {
            color: var(--info-color) !important;
        }

        /* Main Content Layout */
        .main-content {
            padding: 2rem;
            min-height: calc(100vh - 76px);
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
            margin: 0;
        }

        .page-header .text-muted {
            color: var(--text-muted) !important;
        }

        /* Stats Cards */
        .stats-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-purple) 100%);
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(123, 104, 238, 0.15);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-purple) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .stats-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Current User Row Highlight - Softer color */
        .table-info {
            background: rgba(74, 144, 226, 0.08) !important;
            border-left: 3px solid rgba(74, 144, 226, 0.6) !important;
        }

        .table-info td {
            border-bottom: 1px solid rgba(74, 144, 226, 0.15) !important;
            color: #E4E6EB !important;
            background: rgba(74, 144, 226, 0.08) !important;
        }

        .table-info .text-muted {
            color: var(--text-muted) !important;
        }

        /* Form Checkboxes - Fixed styling */
        .form-check {
            margin-bottom: 1rem;
        }

        .form-check-input {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .form-check-input:checked {
            background-color: var(--primary-purple) !important;
            border-color: var(--primary-purple) !important;
        }

        .form-check-label {
            color: var(--text-primary) !important;
            font-weight: 500;
            margin-left: 0.5rem;
        }

        /* Ensure all form labels are visible */
        .form-label,
        label {
            color: var(--text-primary) !important;
            font-weight: 500;
        }

        /* Fix muted text in forms */
        .form-text,
        .text-muted {
            color: var(--text-muted) !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .stats-card {
                padding: 1rem;
            }

            .stats-value {
                font-size: 1.5rem;
            }

            .user-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand" href="<?= site_url('/dashboard') ?>">
                <img src="<?= base_url('public/QuickPuff logoo.png') ?>" alt="QuickPuff" style="height: 4rem;">
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/dashboard') ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <?php if (session()->get('role') === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/staff') ?>">
                            <i class="fas fa-users"></i> Staff
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/products') ?>">
                            <i class="fas fa-boxes-stacked"></i> Stock
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/pos') ?>">
                            <i class="fas fa-shopping-cart"></i> POS
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('/reports/sales') ?>">
                            <i class="fas fa-chart-line"></i> Reports
                        </a>
                    </li>
                </ul>

                <!-- User Info -->
                <div class="navbar-nav">
                    <div class="user-info">
                        <div class="user-avatar">
                            <?= strtoupper(substr(session()->get('full_name', 'U'), 0, 1)) ?>
                        </div>
                        <div class="user-details">
                            <div class="user-name"><?= session()->get('full_name', 'User') ?></div>
                            <div class="user-role"><?= ucfirst(session()->get('role', 'staff')) ?></div>
                        </div>
                        <a class="nav-link" href="<?= site_url('/logout') ?>">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('error') && !session()->get('is_logged_in')): ?>
        <div class="container-fluid mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="container-fluid mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('info')): ?>
        <div class="container-fluid mt-3">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle me-2"></i>
                    <?= session()->getFlashdata('info') ?>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="main-content">
