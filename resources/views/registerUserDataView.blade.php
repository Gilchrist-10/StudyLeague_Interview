<html>
<head>
    <title>Registered Data User</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>

</head>
<body>
     
<div class="container mt-2">
            <center><h4> User Registered Data </h4></center>

      
    <div class="card mt-3">
        <div class="card-body">
            <center><h4> Filter </h4></center>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-3">
                        <label><strong>Pool Number :</strong></label>
                    </div>
                    <div class="col-md-3">
                        <label><strong>Amount :</strong></label>
                    </div>
                    <div class="col-md-3">
                        <label><strong>Name :</strong></label>
                    </div>
                    <div class="col-md-3">
                        <label><strong>Email :</strong></label>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-3">
                        <select id='pool_no' class="form-control filter-data-user" style="width: 200px">
                            <option value="">Pool Number</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control filter-data-user-input" id="amount" name="amount">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control filter-data-user-input" id="name" name="name">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control filter-data-user-input" id="email" name="email">
                    </div>
                </div>
            </div>
        </div>
    </div>

  <div class="card mt-4">
        <div class="card-body">
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Pool Number</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
</div>
</div>
     
</body>
     
<script type="text/javascript">
  $(function () {
      
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('registerUserAccountData') }}",
          data: function (d) {
                d.pool_no = $('#pool_no').val(),
                d.amount = $('#amount').val(),
                d.name = $('#name').val(),
                d.email = $('#email').val(),
                d.search = $('input[type="search"]').val()
            }
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'pool_no', name: 'pool_no'},
            {data: 'amount', name: 'amount'},
        ]
    });
  
    $('.filter-data-user').change(function(){
        table.draw();
    });

    $('.filter-data-user-input').keyup(function(){
        table.draw();
    });


      
  });
</script>
</html>