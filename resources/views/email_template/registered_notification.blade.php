<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <title>Registered Users</title>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <td>{{ $registredNotification['name'] }}</td>
                    </tr>
                    <tr>
                        <th scope="col">Last Name</th>
                        <td>{{ $registredNotification['lname'] }}</td>
                    </tr>
                    <tr>
                        <th scope="col">Email</th>
                        <td>{{ $registredNotification['email'] }}</td>
                    </tr>
                    <tr>
                        <th scope="col">Name on Cart</th>
                        <td>{{ $registredNotification['name_on_card'] }}</td>
                    </tr>
                    <tr>
                        <th scope="col">Amount</th>
                        <td>${{ $registredNotification['amount'] }}</td>
                    </tr>
                    <tr>
                        <th scope="col">Address</th>
                        <td>{{ $registredNotification['address'] }}</td>
                    </tr>
                    <tr>
                        <th scope="col">Contact</th>
                        <td>{{ $registredNotification['contact'] }}</td>
                    </tr>
                    </thead>
                  </table>
            </div>
        </div>
    </div>

</body>
</html>
