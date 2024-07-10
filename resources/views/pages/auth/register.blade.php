@extends('layouts.unauthorizedtemplate')

@section('content')
<div class="container">
    <section class="mt-2 mb-4">
        <div class="container py-3 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-3 text-center">

                            <h4 class="mb-2">Sign Up</h4>

                            <form method="POST" action="{{ route('register.submit') }}">
                            @csrf
                                <div class="form-outline mb-2">
                                    <input type="text" name="fullname" class="form-control form-control-md" />
                                    <label class="form-label" for="fullname">Full Name</label>
                                </div>
                                <div class="form-outline mb-2">
                                    <input type="email" name="email" class="form-control form-control-md" />
                                    <label class="form-label" for="email">Email</label>
                                </div>
                                <div class="form-outline mb-2">
                                    <input type="text" name="pfno" class="form-control form-control-md" />
                                    <label class="form-label" for="pfno">PF Number</label>
                                </div>
                                <div class="form-outline mb-2">
                                    <input type="tel" name="phonenumber" class="form-control form-control-md" />
                                    <label class="form-label" for="pfno">Phone Number</label>
                                </div>

                                <div class="form-outline mb-2">
                                    <input type="password" name="password" class="form-control form-control-md" />
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="form-outline mb-2">
                                    <input type="password" name="confirmpassword" class="form-control form-control-md" />
                                    <label class="form-label" for="confirmpassword">Confirm Password</label>
                                </div> 

                                <button class="btn btn-info btn-md btn-block col-12" type="submit">Sign Up</button>
                            </form>


                            <hr class="my-2">
                            <div>New User ? <a href="login">Login Here</a></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection