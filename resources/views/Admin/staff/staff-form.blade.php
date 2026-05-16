 <!-- Branch ID -->
<div class="mb-3">
    <label class="form-label">Branch ID</label>
    <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" required>
        <option value="">-- Select Branch --</option>
        @foreach($branches as $branch)
            <option value="{{ $branch->branch_id }}" {{ old('branch_id') == $branch->branch_id ? 'selected' : '' }}>
                {{ $branch->branch_id }} ({{ $branch->branch_name }})
            </option>
        @endforeach
    </select>
    @error('branch_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
 <!-- Name -->
 <div class="mb-3">
     <label class="form-label">Name</label>
     <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
         value="{{ old('name') }}" required />
     @error('name')
         <div class="invalid-feedback">{{ $message }}</div>
     @enderror
 </div>

 <!-- Role -->
 <div class="mb-3">
     <label class="form-label">Role</label>
     <select name="role_type" class="form-select  @error('role_type') is-invalid @enderror" required>

         <option value="">-- Select Role --</option>
         <option value="Manager" {{ old('role') == 'Manager' ? 'selected' : '' }}>Manager
         </option>
         <option value="Receptionist" {{ old('role') == 'Receptionist' ? 'selected' : '' }}>
             Receptionist</option>
         <option value="Assistant" {{ old('role') == 'Assistant' ? 'selected' : '' }}>
             Assistant</option>
     </select>
     @error('role_type')
         <div class="invalid-feedback">{{ $message }}</div>
     @enderror
 </div>

 <!-- Phone -->
 <div class="mb-3">
     <label class="form-label">Mobile No</label>
     <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
         value="{{ old('mobile') }}" required />
     @error('mobile')
         <div class="invalid-feedback">{{ $message }}</div>
     @enderror
 </div>

 <!-- Email -->
 <div class="mb-3">
     <label class="form-label">Email</label>
     <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
         value="{{ old('email') }}" required />
     @error('email')
         <div class="invalid-feedback">{{ $message }}</div>
     @enderror
 </div>
 <!-- Password -->
 <div class="mb-3">
     <label class="form-label">Password</label>
     <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required />
     @error('password')
         <div class="invalid-feedback">{{ $message }}</div>
     @enderror
 </div>
