<!DOCTYPE html>
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}">

<head>
	<x-head-partial></x-head-partial>
</head>

<body>
	<div class="wrapper">

		<x-supvis.left-drawer></x-supvis.left-drawer>

		<div class="main">
            <x-top-drawer></x-top-drawer>

			{{ $slot }}

			<!-- <footer class="footer">
				Kosong
			</footer> -->
		</div>
	</div>

	<script src="{{ asset('admin_asset/js/app.js') }}"></script>

</body>

</html>

