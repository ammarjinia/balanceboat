<!DOCTYPE html>

<html>

<head>

    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" crossorigin="anonymous"></script>-->

    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
</head>
<body>
<form action="{{ url('razorpay-payment-store') }}" method="POST" >
    @csrf
    <script src="https://checkout.razorpay.com/v1/checkout.js"
            data-key="{{ env('RAZORPAY_KEY') }}"
            data-amount="1000"
            data-buttontext="Pay"
            data-name="ItSolutionStuff.com"
            data-description="Rozerpay"
            data-image="https://www.itsolutionstuff.com/frontTheme/images/logo.png"
            data-prefill.name="name"
            data-prefill.email="email@gmail.com"
            data-theme.color="#ff7529">
    </script>
</form>
</body>
</html>