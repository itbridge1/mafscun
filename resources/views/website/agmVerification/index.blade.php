<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGM Verification || {{ $_site_setting->title ?? 'MAFSCUN' }}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8faf9;
            font-family: 'Inter', sans-serif;
            color: #333;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .verification-container {
            width: 100%;
            max-width: 520px;
            padding: 20px;
        }
        .card {
            border: none;
            box-shadow: 0 10px 30px rgba(1, 167, 85, 0.08);
            border-radius: 16px;
            background-color: #fff;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(1, 167, 85, 0.12);
        }
        .card-header-brand {
            background: linear-gradient(135deg, #01a755, #008f48);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-bottom: none;
        }
        .card-header-brand h3 {
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
        }
        .card-body {
            padding: 35px 30px;
        }
        .alert-brand-danger {
            background-color: #fdf2f2;
            border: 1px solid #f8b4b4;
            color: #c0392b;
            border-radius: 10px;
            padding: 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-brand-success {
            background-color: #f4fcf7;
            border: 1px solid #ddf3e8;
            color: #01a755;
            border-radius: 10px;
            padding: 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
        }
        .data-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f3f1;
        }
        .data-item:last-child {
            border-bottom: none;
        }
        .data-label {
            color: #666;
            font-weight: 500;
        }
        .data-value {
            font-weight: 600;
            color: #111;
        }
        .btn-home {
            background-color: #01a755;
            border-color: #01a755;
            color: white;
            font-weight: 600;
            border-radius: 30px;
            padding: 12px 30px;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(1, 167, 85, 0.2);
            text-decoration: none !important;
            display: inline-block;
            text-align: center;
        }
        .btn-home:hover {
            background-color: #008f48;
            border-color: #008f48;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(1, 167, 85, 0.3);
        }
        .brand-logo-text {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 5px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <div class="card">
            <div class="card-header-brand">
                <h3>AGM Verification</h3>
                <div class="brand-logo-text">{{ $_site_setting->title ?? 'MAFSCUN' }}</div>
            </div>
            <div class="card-body">
                @if ($data['success'] == 0)
                    <div class="alert-brand-danger mb-4" role="alert">
                        <i class="fas fa-exclamation-circle fa-lg"></i>
                        <span>Representative data not found in our system.</span>
                    </div>
                    <a href="/" class="btn-home">
                        <i class="fas fa-home mr-2"></i> Back to Homepage
                    </a>
                @elseif ($data['success'] == 1)
                    <div class="alert-brand-success" role="alert">
                        <i class="fas fa-check-circle fa-lg"></i>
                        <span>Data verified successfully!</span>
                    </div>
                    
                    <div class="data-list mb-4">
                        <div class="data-item">
                            <span class="data-label">Representative Name</span>
                            <span class="data-value">{{ $agm['name'] ?? 'N/A' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Email Address</span>
                            <span class="data-value">{{ $agm['email'] ?? 'N/A' }}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Mobile Number</span>
                            <span class="data-value">{{ $agm['mobile_no'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <a href="/" class="btn-home">
                        <i class="fas fa-home mr-2"></i> Back to Homepage
                    </a>
                @endif
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>