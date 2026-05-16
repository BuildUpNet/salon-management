<footer class="footer w-100">
    <span id="currentDate" class="text-white small"></span>

    <span class="text-white small text-center flex-grow-1">My Salon Hair & Beauty</span>
    <form  action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" style="all: unset; cursor: pointer;" title="Logout">
            <i class="bi bi-box-arrow-right text-white fs-6"></i>
        </button>
    </form>

</footer>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/js/appointment.js') }}"></script>

</html>
