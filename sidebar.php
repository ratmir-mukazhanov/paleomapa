<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="./css/sidebar.css">
<link rel="stylesheet" href="./css/cores.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<nav class="sidebar-navigation">
	<ul>
		<a href="index.php">   
			<li class="<?= ($currentPage === 'index.php') ? 'active' : '' ?>">
				<i>
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
						<path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"/>
					</svg>
				</i>
				<span class="tooltip">HomePage</span>
			</li>
		</a>

		<a href="mapa.php">   
			<li class="<?= ($currentPage === 'mapa.php') ? 'active' : '' ?>">
				<i>
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
						<path d="m600-120-240-84-186 72q-20 8-37-4.5T120-170v-560q0-13 7.5-23t20.5-15l212-72 240 84 186-72q20-8 37 4.5t17 33.5v560q0 13-7.5 23T812-192l-212 72Zm-40-98v-468l-160-56v468l160 56Zm80 0 120-40v-474l-120 46v468Zm-440-10 120-46v-468l-120 40v474Zm440-458v468-468Zm-320-56v468-468Z"/>
					</svg>
				</i>
				<span class="tooltip">Map</span>
			</li>
		</a>

		<a href="routing.html">
			<li class="<?= ($currentPage === 'routing.html') ? 'active' : '' ?>">
				<i>
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
						<path d="M320-360h80v-120h140v100l140-140-140-140v100H360q-17 0-28.5 11.5T320-520v160ZM480-80q-15 0-29.5-6T424-104L104-424q-12-12-18-26.5T80-480q0-15 6-29.5t18-26.5l320-320q12-12 26.5-18t29.5-6q15 0 29.5 6t26.5 18l320 320q12 12 18 26.5t6 29.5q0 15-6 29.5T856-424L536-104q-12 12-26.5 18T480-80ZM320-320l160 160 320-320-320-320-320 320 160 160Zm160-160Z"/>
					</svg>
				</i>
				<span class="tooltip">Routing</span>
			</li>
		</a>

		<a href="about_us.php">
			<li class="<?= ($currentPage === 'about_us.php') ? 'active' : '' ?>">
				<i>
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
						<path d="M440-280h80v-240h-80v240Zm40-320q17 0 28.5-11.5T520-640q0-17-11.5-28.5T480-680q-17 0-28.5 11.5T440-640q0 17 11.5 28.5T480-600Zm0 520q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/>
					</svg>
				</i>
				<span class="tooltip">About Us</span>
			</li>
		</a>

		<a href="contact_us.php">
			<li class="<?= ($currentPage === 'contact_us.php') ? 'active' : '' ?>">
				<i>
					<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
						<path d="m480-80-10-120h-10q-142 0-241-99t-99-241q0-142 99-241t241-99q71 0 132.5 26.5t108 73q46.5 46.5 73 108T800-540q0 75-24.5 144t-67 128q-42.5 59-101 107T480-80Zm80-146q71-60 115.5-140.5T720-540q0-109-75.5-184.5T460-800q-109 0-184.5 75.5T200-540q0 109 75.5 184.5T460-280h100v54Zm-101-95q17 0 29-12t12-29q0-17-12-29t-29-12q-17 0-29 12t-12 29q0 17 12 29t29 12Zm-29-127h60q0-30 6-42t38-44q18-18 30-39t12-45q0-51-34.5-76.5T460-720q-44 0-74 24.5T344-636l56 22q5-17 19-33.5t41-16.5q27 0 40.5 15t13.5 33q0 17-10 30.5T480-558q-35 30-42.5 47.5T430-448Zm30-65Z"/>
					</svg>
				</i>
				<span class="tooltip">Contacts</span>
			</li>
		</a>
	</ul>
</nav>

<script src="./js/sidebar.js"></script>
