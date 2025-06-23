<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$query = mysqli_query($koneksi, "SELECT 
    produk.foto_produk AS gambar,
    produk.nama_produk,
    produk.harga_produk,
    pembayaran.total_bayar,
    pembayaran.pembayaran,
    pesanan.status,
    pesanan.jumlah_produk,
    pesanan.alamat,
    ongkir.nama_kota,
    users.nama,
    users.no_hp
FROM pembayaran
LEFT JOIN pesanan ON pembayaran.id_pesanan = pesanan.id_pesanan
LEFT JOIN produk ON pesanan.id_produk = produk.id_produk
LEFT JOIN ongkir ON pembayaran.id_ongkir = ongkir.id_ongkir
LEFT JOIN users ON pesanan.id_user = users.id_user
WHERE users.id_user = '$id_user' AND pesanan.status = 'Canceled'
ORDER BY pesanan.tgl_pesan DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <!-- icon atas -->
  <link rel="shortcut icon" type="image/png/jpg" href="asset/icon putih meijin.png">
  <title>Meijin Meals</title>

  <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css' rel='stylesheet'>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="shortcut icon" type="image/png/jpg" href="asset/icon meijin.png">
  <link href='https://fonts.googleapis.com/css2?family=Trispace:wght@100..800&display=swap' rel='stylesheet'>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=undo" />

  <style>
    * {
      margin: 0; 
      padding: 0; 
      box-sizing: border-box;
      font-family: 'Trispace', sans-serif;
    }

    body {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      min-height: 100vh;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
    }

    /* Header Section */
    .header-section {
      background: linear-gradient(135deg, #811A1A 0%, #a52a2a 100%);
      color: white;
      padding: 2rem 0;
      margin-bottom: 2rem;
      border-radius: 0 0 25px 25px;
      box-shadow: 0 10px 30px rgba(129, 26, 26, 0.3);
    }

    .header-content {
      display: flex;
      align-items: center;
      gap: 20px;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .back-btn {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .back-btn:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: translateY(-2px);
    }

    .page-title {
      font-size: 2.5rem;
      font-weight: 700;
      margin: 0;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    /* Navigation Tabs */
    .order-nav {
      background: white;
      border-radius: 20px;
      padding: 15px 20px;
      margin-bottom: 30px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: space-around;
      gap: 20px;
    }

    .order-nav a {
      font-size: 16px;
      font-weight: 600;
      color: #6c757d;
      text-decoration: none;
      padding: 12px 25px;
      border-radius: 15px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .order-nav a:hover,
    .order-nav a.active {
      color: white;
      background: linear-gradient(135deg, #811A1A 0%, #a52a2a 100%);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(129, 26, 26, 0.4);
    }

    /* Order Cards */
    .order-card {
      background: white;
      border-radius: 20px;
      margin-bottom: 25px;
      padding: 30px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      border: 1px solid #f0f0f0;
      position: relative;
      overflow: hidden;
    }

    .order-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(135deg, #811A1A 0%, #a52a2a 100%);
    }

    .order-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    /* Status Badge */
    .status-badge {
      display: inline-block;
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
      color: white;
      padding: 8px 20px;
      border-radius: 25px;
      font-weight: 600;
      font-size: 14px;
      margin-bottom: 20px;
      box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
      /* animation: pulse 2s infinite; */
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }

    /* Product Info */
    .product-section {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 20px;
    }

    .product-image {
      width: 100px;
      height: 100px;
      border-radius: 15px;
      object-fit: cover;
      border: 3px solid #f8f9fa;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .product-image:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .product-details h5 {
      color: #2c3e50;
      font-weight: 700;
      margin-bottom: 8px;
      font-size: 1.2rem;
    }

    .product-price {
      color: #811A1A;
      font-weight: 600;
      font-size: 1.1rem;
    }

    /* Order Details Grid */
    .order-details {
      display: grid;
      grid-template-columns: auto 1fr auto 1fr;
      gap: 20px;
      align-items: center;
      background: #f8f9fa;
      padding: 20px;
      border-radius: 15px;
      margin-top: 20px;
    }

    .detail-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .detail-label {
      font-size: 12px;
      color: #6c757d;
      font-weight: 600;
      text-transform: uppercase;
      margin-bottom: 5px;
    }

    .detail-value {
      font-size: 16px;
      font-weight: 700;
      color: #2c3e50;
    }

    /* Customer Info */
    .customer-info {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      padding: 20px;
      border-radius: 15px;
      margin-top: 20px;
      border-left: 5px solid #811A1A;
    }

    .customer-info h6 {
      color: #811A1A;
      font-weight: 700;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .customer-details {
      color: #495057;
      line-height: 1.6;
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      background: white;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .empty-icon {
      font-size: 80px;
      color: #dee2e6;
      margin-bottom: 20px;
    }

    .empty-message {
      color: #6c757d;
      font-size: 18px;
      font-weight: 500;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .container {
        padding: 0 15px;
      }
      
      .page-title {
        font-size: 2rem;
      }
      
      .order-nav {
        flex-direction: column;
        gap: 10px;
      }
      
      .order-details {
        grid-template-columns: 1fr;
        gap: 15px;
      }
      
      .product-section {
        flex-direction: column;
        text-align: center;
      }
    }

    /* Loading Animation */
    .loading {
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 3px solid #f3f3f3;
      border-top: 3px solid #811A1A;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>

<body>
  <!-- Header Section -->
  <div class="header-section">
    <div class="header-content">
      <a href="user.php" class="back-btn">
        <span class="material-symbols-outlined" style="font-size:30px; color:white;">undo</span>
      </a>
      <h1 class="page-title">My Orders</h1>
    </div>
  </div>

  <div class="container">

    <!-- Navigation Tab -->
    <div class="order-nav">
      <a href="order.php">
        <i class="bi bi-list-ul me-2"></i>Semua</a>
      <a href="produk-pending.php">
        <i class="bi bi-clock-history me-2"></i>Pending</a>
      <a href="produk-delivery.php">
        <i class="bi bi-truck me-2"></i>Delivery</a>
      <a href="produk-completed.php">
        <i class="bi bi-check-circle me-2"></i>Completed</a>
        <a href="produk-canceled.php" class="active">
        <i class="bi bi-x-circle me-2"></i>Canceled</a>
    </div>

    <!-- Order Card -->
    <?php
    if (mysqli_num_rows($query) > 0) {
      mysqli_data_seek($query, 0);
      while ($data = mysqli_fetch_assoc($query)) {
    ?>
      <div class="order-card">
        <!-- Status -->
        <div class="status-badge">
          <i class="bi bi-x-circle me-2"></i><?= htmlspecialchars($data['status']); ?>
        </div>

        <!-- Product  -->
        <div class="product-section">
          <img src="asset/menu/<?= htmlspecialchars($data['gambar']); ?>" 
               alt="<?= htmlspecialchars($data['nama_produk']); ?>" 
               class="product-image"
               onerror="this.src='asset/default.png'">
          
          <div class="product-details flex-grow-1">
            <h5><?= htmlspecialchars($data['nama_produk']); ?></h5>
            <div class="product-price">
              <i class="bi bi-tag me-2"></i>Rp<?= number_format($data['harga_produk'], 0, ',', '.'); ?>
            </div>
          </div>
        </div>

        <!-- Order Details -->
        <div class="order-details">
          <div class="detail-item">
            <div class="detail-label">Jumlah Produk</div>
            <div class="quantity-badge">x<?= $data['jumlah_produk']; ?></div>
          </div>
          
          <div class="detail-item">
            <div class="detail-label">Total Pesanan</div>
            <div class="total-price">Rp<?= number_format($data['total_bayar'], 0, ',', '.'); ?></div>
          </div>

          <div class="detail-item">
            <div class="detail-label">Pembayaran</div>
            <div class="total-price"><?=($data['pembayaran'])?></div>
          </div>
        </div>

        <!-- Customer Information -->
        <div class="customer-info">
          <h6>
            <i class="bi bi-person-circle"></i>
            Customer Information
          </h6>
          <div class="customer-details">
            <div><strong><?= htmlspecialchars($data['nama']); ?></strong></div>
            <div><i class="bi bi-telephone me-2"></i><?= htmlspecialchars($data['no_hp']); ?></div>
            <div><i class="bi bi-geo-alt me-2"></i><?= htmlspecialchars($data['alamat']); ?></div>
          </div>
        </div>
      </div>

    <?php
      }
    } else {
    ?>
      <!-- Empty State -->
      <div class="empty-state">
        <div class="empty-icon">
          <i class="bi bi-emoji-smile"></i>
        </div>
        <div class="empty-message">
          <h4>No Canceled Orders</h4>
          <p>You do not have any cancelled orders at this time.</p>
        </div>
      </div>
    <?php
    }
    ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>