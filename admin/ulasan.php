<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = intval($_POST['delete_id']);

    $stmt = $koneksi->prepare("DELETE FROM testimoni WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$sql = "SELECT * FROM testimoni ORDER BY tanggal DESC";
$result = $koneksi->query($sql);

$testimoniList = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $testimoniList[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <?php
    $user = [
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150'
    ];

    // Menu sidebar
    $menuItems = [
        ['icon' => 'fas fa-box', 'label' => 'Produk', 'url' => 'index.php', 'active' => false],
        ['icon' => 'fas fa-star', 'label' => 'Ulasan', 'url' => 'ulasan.php', 'active' => true],
    ];
    ?>

    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-gray-900 text-white w-64 min-h-screen flex flex-col transition-all duration-300 ease-in-out">
            <!-- Logo -->
            <div class="flex items-center justify-center py-6 px-4 border-b border-gray-700">
                <div class="flex items-center space-x-3">
                    <span class="text-xl font-bold">Zoi Merch</span>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-4 py-6">
                <ul class="space-y-2">
                    <?php foreach ($menuItems as $item): ?>
                        <li>
                            <a href="<?= $item['url'] ?>"
                                class="flex items-center space-x-3 py-3 px-4 rounded-lg transition-colors duration-200 hover:bg-gray-800 <?= $item['active'] ? 'bg-blue-600 text-white' : 'text-gray-300' ?>">
                                <i class="<?= $item['icon'] ?> w-5"></i>
                                <span><?= $item['label'] ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <!-- User Profile -->
            <div class="px-4 py-4 border-t border-gray-700">
                <div class="flex items-center space-x-3">
                    <img src="<?= $user['avatar'] ?>" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate"><?= $user['name'] ?></p>
                        <p class="text-xs text-gray-400 truncate"><?= $user['email'] ?></p>
                    </div>
                    <a href="logout.php" class="text-gray-400 hover:text-white">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <button id="sidebarToggle" class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 id="pageTitle" class="text-2xl font-semibold text-gray-900">Ulasan</h1>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Statistics Cards for Reviews -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <?php
                    $totalTestimoni = count($testimoniList);
                    $ratingCounts = array_count_values(array_column($testimoniList, 'rate'));
                    $avgRating = array_sum(array_column($testimoniList, 'rate')) / $totalTestimoni;
                    ?>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Ulasan</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2"><?= $totalTestimoni ?></p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-comments text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Rating Rata-rata</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2"><?= number_format($avgRating, 1) ?></p>
                                <div class="flex items-center mt-2">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star text-sm <?= $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-star text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Ulasan Positif</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2"><?= ($ratingCounts[5] ?? 0) + ($ratingCounts[4] ?? 0) ?></p>
                                <p class="text-sm text-green-600 mt-2">Rating 4-5 bintang</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-thumbs-up text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Perlu Perhatian</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2"><?= ($ratingCounts[1] ?? 0) + ($ratingCounts[2] ?? 0) ?></p>
                                <p class="text-sm text-red-600 mt-2">Rating 1-2 bintang</p>
                            </div>
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter dan Search -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                        <div class="flex flex-col sm:flex-row gap-4 items-center">
                            <div class="relative">
                                <input type="text" id="searchReview" placeholder="Cari ulasan..."
                                    class="bg-gray-100 border-0 rounded-lg py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white w-full sm:w-auto">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>

                            <select id="filterRating" class="bg-gray-100 border-0 rounded-lg py-2 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                                <option value="">Semua Rating</option>
                                <option value="5">5 Bintang</option>
                                <option value="4">4 Bintang</option>
                                <option value="3">3 Bintang</option>
                                <option value="2">2 Bintang</option>
                                <option value="1">1 Bintang</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-600">Urutkan:</span>
                            <select id="sortReview" class="bg-gray-100 border-0 rounded-lg py-2 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                                <option value="newest">Terbaru</option>
                                <option value="oldest">Terlama</option>
                                <option value="highest">Rating Tertinggi</option>
                                <option value="lowest">Rating Terendah</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Review Cards -->
                <div class="space-y-6" id="reviewContainer">
                    <?php foreach ($testimoniList as $testimoni): ?>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 review-card"
                            data-rating="<?= $testimoni['rate'] ?>" data-date="<?= $testimoni['tanggal'] ?>">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center space-x-4">
                                        <img src="../image/Profile.png" alt="<?= $testimoni['name'] ?>"
                                            class="w-12 h-12 rounded-full object-cover shadow-sm">
                                        <div>
                                            <h4 class="font-semibold text-gray-900"><?= $testimoni['name'] ?></h4>
                                            <p class="text-sm text-gray-500"><?= date('d M Y', strtotime($testimoni['tanggal'])) ?></p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <div class="flex items-center">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star text-lg <?= $i <= $testimoni['rate'] ? 'text-yellow-400' : 'text-gray-300' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="bg-<?= $testimoni['rate'] >= 4 ? 'green' : ($testimoni['rate'] >= 3 ? 'yellow' : 'red') ?>-100 text-<?= $testimoni['rate'] >= 4 ? 'green' : ($testimoni['rate'] >= 3 ? 'yellow' : 'red') ?>-800 px-2 py-1 rounded-full text-sm font-medium">
                                            <?= $testimoni['rate'] ?>/5
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <p class="text-gray-700 leading-relaxed"><?= $testimoni['comment'] ?></p>
                                </div>

                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <div class="flex items-center space-x-4">
                                        <!-- <button class="flex items-center space-x-2 text-gray-500 hover:text-blue-600 transition-colors duration-200">
                                            <i class="fas fa-thumbs-up"></i>
                                            <span class="text-sm">Helpful</span>
                                        </button>
                                        <button class="flex items-center space-x-2 text-gray-500 hover:text-green-600 transition-colors duration-200">
                                            <i class="fas fa-reply"></i>
                                            <span class="text-sm">Reply</span>
                                        </button> -->
                                    </div>

                                    <div class="flex items-center">
                                        <button
                                            onclick="confirmDelete(<?= $testimoni['id'] ?>)"
                                            class="text-red-600 hover:text-red-800 transition-colors duration-200 text-sm font-medium">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal untuk melihat ulasan lengkap -->
    <div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-96 overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Ulasan Lengkap</h3>
                <button onclick="closeReviewModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <p id="fullReviewText" class="text-gray-900 leading-relaxed"></p>
            </div>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>
    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
            <h2 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h2>
            <p class="text-gray-700 mb-4">Apakah Anda yakin ingin menghapus ulasan ini?</p>
            <form method="POST">
                <input type="hidden" name="delete_id" id="delete_id">
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Batal</button>
                    <button type="submit" name="delete" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            document.getElementById('delete_id').value = id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Sidebar toggle functionality
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        });

        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });

        // Make sidebar responsive
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });

        // Initialize responsive sidebar
        if (window.innerWidth < 1024) {
            sidebar.classList.add('-translate-x-full');
        }

        // Menu item click handlers
        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('href') === 'index.php') {
                    // Allow navigation to index.php
                    return true;
                }

                e.preventDefault();

                // Remove active class from all menu items
                document.querySelectorAll('nav a').forEach(item => {
                    item.classList.remove('bg-blue-600', 'text-white');
                    item.classList.add('text-gray-300');
                });

                // Add active class to clicked item
                this.classList.add('bg-blue-600', 'text-white');
                this.classList.remove('text-gray-300');

                // Update page title
                const pageTitle = this.querySelector('span').textContent;
                document.getElementById('pageTitle').textContent = pageTitle;

                // Close mobile sidebar
                if (window.innerWidth < 1024) {
                    sidebar.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                }
            });
        });

        // Review filtering and searching functionality
        const searchInput = document.getElementById('searchReview');
        const ratingFilter = document.getElementById('filterRating');
        const sortSelect = document.getElementById('sortReview');
        const reviewContainer = document.getElementById('reviewContainer');

        function filterReviews() {
            const searchTerm = searchInput?.value.toLowerCase() || '';
            const selectedRating = ratingFilter?.value || '';
            const sortBy = sortSelect?.value || 'newest';

            const reviewCards = Array.from(document.querySelectorAll('.review-card'));

            // Filter reviews
            reviewCards.forEach(card => {
                const reviewText = card.textContent.toLowerCase();
                const cardRating = card.getAttribute('data-rating');

                const matchesSearch = reviewText.includes(searchTerm);
                const matchesRating = !selectedRating || cardRating === selectedRating;

                if (matchesSearch && matchesRating) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Sort visible reviews
            const visibleCards = reviewCards.filter(card => card.style.display !== 'none');

            visibleCards.sort((a, b) => {
                const dateA = new Date(a.getAttribute('data-date'));
                const dateB = new Date(b.getAttribute('data-date'));
                const ratingA = parseInt(a.getAttribute('data-rating'));
                const ratingB = parseInt(b.getAttribute('data-rating'));

                switch (sortBy) {
                    case 'oldest':
                        return dateA - dateB;
                    case 'highest':
                        return ratingB - ratingA;
                    case 'lowest':
                        return ratingA - ratingB;
                    default: // newest
                        return dateB - dateA;
                }
            });

            // Reorder DOM elements
            visibleCards.forEach(card => reviewContainer?.appendChild(card));
        }

        // Add event listeners for filtering
        if (searchInput) searchInput.addEventListener('input', filterReviews);
        if (ratingFilter) ratingFilter.addEventListener('change', filterReviews);
        if (sortSelect) sortSelect.addEventListener('change', filterReviews);

        // Modal functions
        function showFullReview(reviewText) {
            document.getElementById('fullReviewText').textContent = reviewText;
            document.getElementById('reviewModal').classList.remove('hidden');
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('reviewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReviewModal();
            }
        });
    </script>
</body>

</html>