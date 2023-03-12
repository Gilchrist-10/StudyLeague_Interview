<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link rel="stylesheet" href="https://unpkg.com/flowbite@latest/dist/flowbite.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body>
	<div class="mx-auto md:h-screen flex flex-col justify-center items-center px-6 pt-8 pt:mt-0">
		<div class="mx-auto md:h-screen flex flex-col justify-center items-center px-6 pt-8 pt:mt-0">
		    <a href="#" class="text-2xl font-semibold flex justify-center items-center mb-8 lg:mb-10">
		      <img src="https://studyleagueitsolutions.com/wp-content/uploads/elementor/thumbs/Screenshot-2022-02-21-at-8.55.48-PM-pkuf7ppqhtme6jwxstbshjs5hfqorq6qdb5tnwsyn0.png" class="h-11 mr-4" alt="FlowBite Logo">
		    </a>
		    <div class="bg-white shadow rounded-lg lg:flex items-center justify-center md:mt-0 w-full lg:max-w-screen-lg 2xl:max:max-w-screen-lg xl:p-0">
				<div class="hidden lg:flex w-2/3">
					<img class="rounded-l-lg" src="https://flowbite.com/application-ui/demo/images/authentication/login.jpg" alt="login image">
				</div>
				<div class="w-full p-6 sm:p-8 lg:p-16 lg:py-0 space-y-8">
					<!-- Authentication form goes here -->
					<h2 class="text-2xl lg:text-3xl font-bold text-gray-900">
			   			Register to the Platform
					</h2>
					<!-- <form class="mt-8 space-y-6" action="#"> -->
					   <div>
					      <label for="name" class="text-sm font-medium text-gray-900 block mb-2">Name</label>
					      <input type="text" name="name" id="name" placeholder="Please Enter you Name"
					         class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
					          >
					   </div>
					   <div>
					      <label for="email" class="text-sm font-medium text-gray-900 block mb-2">Email Id</label>
					      <input type="email" name="email" id="email"
					         class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
					         placeholder="Please Enter you Email ID"  >
					   </div>

                    <div class="mt-4 captcha">
                        <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 rounded-l-md border border-r-0 border-gray-300 dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                            {!! captcha_img() !!}
                        </span>
                         <button type="button" class="text-blue-700 border border-blue-700 hover:bg-blue-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:focus:ring-blue-800 btn-refresh" id="regen-captcha" >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </button>
                    </div>
                     <div>
					      <input type="text" name="captcha" id="captcha"
					         class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
					         placeholder="Please Enter Captcha"  >
					   </div>
					   <button 
					      class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-base px-5 py-3 w-full sm:w-auto text-center" id="registerAccount">Register User</button>
					<!-- </form> -->
				</div>
			</div>
		</div>
	</div>
<script src="https://unpkg.com/flowbite@latest/dist/flowbite.bundle.js"></script>
</body>
<script type="text/javascript">

	$(".btn-refresh").click(function(){
  $.ajax({
     type:'GET',
     url:'/refresh_captcha',
     success:function(data){
        $(".captcha span").html(data.captcha);
     }
  });
});


	$('#registerAccount').on('click',function(){
      let _token   = $('meta[name="csrf-token"]').attr('content');
	  let name = $('#name').val();
	  let email = $('#email').val();
	  let captcha = $('#captcha').val();
	  	$.ajax({
            url: "/registerUserAccount",
            type:"POST",
            data:{
              name:name,
              captcha:captcha,
              email:email,
              _token:_token,
        	},
            success:function(response){
	            console.log(response);
             	if(response){
             	 	Swal.fire(
		                'Good!',
		                response.success,
		                'success'
	                )
             	 	window.location.reload();
                }
            },
            error: function(error) {
	            console.log(error.responseJSON.error);
	            if(error){
	                Swal.fire({
	                  icon: 'error',
	                  title: 'Oops...',
	                  text: error.responseJSON.error,
	                })
	                $('.btn-refresh').click();
                }
            }
       });
	});
</script>
</html>