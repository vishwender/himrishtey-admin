<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Select Site</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        .sites {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(250px, 1fr));

            gap: 20px;
        }

        .site {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow:
                0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .site h3 {
            margin-top: 0;
        }

        button {
            width: 100%;
            padding: 12px;
            cursor: pointer;
        }
    </style>

</head>

<body>

    <div class="container">

        <h1>Select Website</h1>

        <p>
            Choose the matrimonial website you want
            to manage.
        </p>

        @if ($errors->any())

        <div>
            {{ $errors->first() }}
        </div>

        @endif

        <div class="sites">

            @foreach ($sites as $site)

            <div class="site">

                <h3>
                    {{ $site->name }}
                </h3>

                <p>
                    {{ $site->domain }}
                </p>

                <form
                    method="POST"
                    action="{{ route('admin.site.switch', $site->id) }}">

                    @csrf

                    <button type="submit">
                        Manage {{ $site->name }}
                    </button>

                </form>

            </div>

            @endforeach

        </div>

    </div>

</body>

</html>