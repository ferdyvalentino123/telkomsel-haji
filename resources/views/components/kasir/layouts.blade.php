<!DOCTYPE html>
<html lang="en">

<head>
	<x-head-partial></x-head-partial>
</head>

<body>
	<div class="wrapper">
        
		@include('components.kasir.left-drawer')

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
