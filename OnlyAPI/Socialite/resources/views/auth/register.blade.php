<form class="form-horizontal" method="POST" action="{{ route('register') }}">
    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <button type="submit" class="btn btn-primary">
                Register
            </button>
        </div>
    </div>
    <hr>
    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <a href="{{ url('/login/graph') }}" class="btn btn-github"><i class="fa fa-github"></i>Office 365</a>
            <!--
                <a href="{{ url('/login/github') }}" class="btn btn-github"><i class="fa fa-github"></i> Github</a>
                <a href="{{ url('/login/twitter') }}" class="btn btn-twitter" class="btn btn-twitter"><i class="fa fa-twitter"></i> Twitter</a>
                <a href="{{ url('/login/facebook') }}" class="btn btn-facebook" class="btn btn-facebook"><i class="fa fa-facebook"></i> Facebook</a>
            -->
        </div>
    </div>
</form>
