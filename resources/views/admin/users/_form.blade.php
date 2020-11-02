<div class="col-md-8">
    <div class="row">
        <div class="col-md-8">
            <div class="form-group margin-b-5 margin-t-5{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name">{{__a('user_page.name')}} *</label>
                <input type="text" class="form-control" name="name" placeholder="{{__a('user_page.name')}}" value="{{ old('name', $record->name) }}" required>

                @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
            <!-- /.form-group -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group margin-b-5 margin-t-5{{ $errors->has('phone') ? ' has-error' : '' }}">
                <label for="phone">{{__a('user_page.phone')}}</label>
                <input type="text" class="form-control" name="phone" placeholder="{{__a('user_page.phone')}}" value="{{ old('phone', $record->phone) }}">

                @if ($errors->has('phone'))
                <span class="help-block">
                    <strong>{{ $errors->first('phone') }}</strong>
                </span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group margin-b-5 margin-t-5{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email">Email *</label>
                <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email', $record->email) }}" required>

                @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group margin-b-5 margin-t-5{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password">{{__a('user_page.password')}} @if(!$record->id) * @endif</label>
                <input type="password" class="form-control" name="password"
                       placeholder="******" autocomplete="off">

                @if ($errors->has('password'))
                    <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
            <!-- /.form-group -->
        </div>
        <!-- /.col-md-12 -->

        <div class="col-md-6">
            <div class="form-group margin-b-5 margin-t-5{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <label for="password_confirmation">{{__a('user_page.password_confirmation')}} @if(!$record->id) * @endif</label>
                <input type="password" class="form-control" name="password_confirmation"
                       placeholder="******">

                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
                @endif
            </div>
            <!-- /.form-group -->
        </div>
        <!-- /.col-md-12 -->

    </div>
</div>
