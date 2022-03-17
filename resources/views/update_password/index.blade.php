<x-updatepassword-layout>
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">

                    <div class="authincation-content">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    @foreach ($errors->all() as $error)
                                        <p class="text-danger">{{ $error }}</p>
                                    @endforeach
                                    <form action="{{ url('password/password-updated') }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label class="mb-1 text-white"><strong>Current Password</strong></label>
                                            <input type="password" name="current_password" class="form-control" placeholder="Current Password">
                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1 text-white"><strong>New Password</strong></label>
                                            <input type="password" name="new_password" class="form-control" placeholder="New Password">
                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1 text-white"><strong>Confirm Password</strong></label>
                                            <input type="password" name="new_confirm_password" placeholder="Confirm Password" class="form-control" >
                                        </div>
                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn bg-white text-primary btn-block">RESET PASSWORD</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-updatepassword-layout>

<style>
    .form-control {
    border-radius: 0.35rem !important;
    }

    .authincation-content {
    background: #03c6ad !important;
    }
    .btn {
    border-radius: 0.35rem !important;
    }
</style>
