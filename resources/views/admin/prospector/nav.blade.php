<nav class="navbar navbar-module navbar-static-top">
        <div class="container">

            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#prospector-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>


            </div>

            <div class="collapse navbar-collapse" id="prospector-navbar-collapse">
                <!-- Left Side Of Navbar -->
                @if (Auth::user())
                <ul class="nav navbar-nav">
                    <li><a href="{{ route('prospector.accounts.index')}}">Accounts</a></li>
                    <li><a href="{{ route('prospector.contacts.index') }}">Contacts</a></li>
                    <li><a href="#">Tasks</a></li>
                    
                </ul>
                @endif


            </div>
        </div>
    </nav>
