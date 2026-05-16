<div class="col-md-6 mb-3">
    <label class="form-label">Branch Name</label>
    <input type="text" name="branch_name"
           class="form-control @error('branch_name') is-invalid @enderror"
           value="{{ old('branch_name') }}" required />
    @error('branch_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">City</label>
    <input type="text" name="city"
           class="form-control @error('city') is-invalid @enderror"
           value="{{ old('city') }}" required />
    @error('city')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">State</label>
    <input type="text" name="state"
           class="form-control @error('state') is-invalid @enderror"
           value="{{ old('state') }}" required />
    @error('state')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Postal Code</label>
    <input type="text" name="postal_code"
           class="form-control @error('postal_code') is-invalid @enderror"
           value="{{ old('postal_code') }}" required />
    @error('postal_code')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Latitude</label>
    <input type="number" name="latitude" step="any"
           class="form-control @error('latitude') is-invalid @enderror"
           value="{{ old('latitude') }}" />
    @error('latitude')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Longitude</label>
    <input type="number" name="longitude" step="any"
           class="form-control @error('longitude') is-invalid @enderror"
           value="{{ old('longitude') }}" />
    @error('longitude')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Number of Chairs</label>
    <input type="number" name="number_of_chairs"
           class="form-control @error('number_of_chairs') is-invalid @enderror"
           value="{{ old('number_of_chairs') }}" required />
    @error('number_of_chairs')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password"
           class="form-control @error('password') is-invalid @enderror"
           required />
    @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="col-md-12 mb-3">
    <label class="form-label"> Enter Email for login Otp Verification</label>
    <input type="email" name="otp_email"
           class="form-control @error('email') is-invalid @enderror"
           required />
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>