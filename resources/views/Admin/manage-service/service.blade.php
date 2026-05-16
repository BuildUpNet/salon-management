 <div class="mb-3">
                                <label class="form-label">Service Name</label>
                                <input type="text" name="service_name"
                                    class="form-control @error('service_name') is-invalid @enderror"
                                    value="{{ old('service_name') }}" />
                                @error('service_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                    <option disabled {{ old('gender') ? '' : 'selected' }}>Select gender</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Unisex" {{ old('gender') == 'Unisex' ? 'selected' : '' }}>Unisex</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Duration</label>
                                <input type="number" name="duration"
                                    class="form-control @error('duration') is-invalid @enderror"
                                    value="{{ old('duration') }}" />
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <input type="text" name="price"
                                    class="form-control @error('price') is-invalid @enderror"
                                    value="{{ old('price') }}" />
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select @error('category') is-invalid @enderror">
                                    <option disabled {{ old('category') ? '' : 'selected' }}>Select category</option>
                                    <option value="Haircut" {{ old('category') == 'Haircut' ? 'selected' : '' }}>Haircut
                                    </option>
                                    <option value="Facial" {{ old('category') == 'Facial' ? 'selected' : '' }}>Facial
                                    </option>
                                    <option value="Spa" {{ old('category') == 'Spa' ? 'selected' : '' }}>Spa</option>
                                    <option value="Massage" {{ old('category') == 'Massage' ? 'selected' : '' }}>Massage
                                    </option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> --}}
                        </div>
