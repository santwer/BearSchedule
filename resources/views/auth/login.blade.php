<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ env('APP_NAME', 'Education') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
</head>

<body>
<section class="hero is-success is-fullheight" id="sectionContent">
    <div class="hero-body">
        <div class="container has-text-centered">
            <div class="column is-4 is-offset-4">
                <h3 class="title has-text-black">Login</h3>
                <hr class="login-hr">
                <p class="subtitle has-text-black">Please login to proceed.</p>
                <div class="box">
                    <figure class="avatar">
                        <img src="https://placehold.it/128x128">
                    </figure>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="field">
                            <div class="control">
                                <b-field @error('email') label="Error"
                                         type="is-danger"
                                         message="{{ $message }}" @enderror>
                                    <b-input placeholder="{{ __('E-Mail Address') }}"
                                             type="email"
                                             name="email"
                                             value="{{ old('email') }}"
                                             icon="email">
                                    </b-input>
                                </b-field>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <b-field @error('password') label="Error"
                                         type="is-danger"
                                         message="{{ $message }}" @enderror>
                                    <b-input placeholder="{{ __('Password') }}"
                                             type="password"
                                             name="password"
                                             icon="key">
                                    </b-input>
                                </b-field>
                            </div>
                        </div>
                        <div class="field">
                            <label class="checkbox">
                                <b-checkbox name="remember"  :value="{{ old('remember') ? 'true' : 'false' }}">
                                    {{ __('Remember Me') }}
                                </b-checkbox>

                            </label>
                        </div>
                        <button class="button is-block is-info is-large is-fullwidth" type="submit">Login</button>
                    </form>
                </div>
                <p class="has-text-grey">
                    <a href="../">Sign Up</a> &nbsp;·&nbsp;
                    <a href="../">Forgot Password</a> &nbsp;·&nbsp;
                    <a href="../">Need Help?</a>
                </p>
            </div>
        </div>
    </div>
</section>
<script async type="text/javascript" src="{{ mix('js/login.js') }}"></script>
</body>

</html>
