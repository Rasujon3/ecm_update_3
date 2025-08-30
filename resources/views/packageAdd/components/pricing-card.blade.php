<div class="card shadow-sm position-relative" style="background-color: #f9fbff;">
    <!-- Subtitle Badge -->
    @if($plan->sub_title)
        <div class="position-absolute top-0 start-50 translate-middle-x bg-primary text-white rounded-pill py-2 px-4 px-md-5 text-center" style="max-width: 80%;">
            <span class="text-truncate">{{ $plan->sub_title }}</span>
        </div>
    @endif

    <div class="card-body pt-5">
        <!-- Package Name -->
        <h5 class="card-title text-primary fw-bold mb-3" style="font-size: 1.7rem;">
            {{ $plan->package_name }}
        </h5>

        <!-- Short Description -->
        <p class="card-text text-secondary mb-2">
            <span class="text-primary">{{ $plan->short_description }}</span>
        </p>

        <!-- Price -->
        <h5 class="card-title text-primary fw-bold mb-3" style="font-size: 2rem;">
            ৳ {{ $plan->price }}
        </h5>

        <hr class="my-3">

        <!-- Start Plan Button -->
        <div class="mb-4">
            <a href="{{ url('/create-shop?plan=' . $plan->id) }}" class="btn btn-primary w-100 fs-5">
                Start Plan
            </a>
        </div>

        <hr class="my-3">

        <!-- Services List -->
        <div class="mb-4">
            @foreach($plan->services as $service)
                <p class="d-flex align-items-center gap-3 mb-3">
                    <span>
                        <i class="bi bi-check-circle-fill text-success"></i>
                    </span>
                    <span class="text-primary">{{ $service->title }}</span>
                </p>
            @endforeach
        </div>

        <!-- Demo Link -->
        @if($plan->demo_url)
            <a href="{{ $plan->demo_url }}" target="_blank" class="d-flex justify-content-center align-items-center bg-success text-white py-2 px-4 rounded-bottom text-decoration-none">
                <span class="text-truncate d-flex align-items-center gap-2">
                    <i class="bi bi-eye fs-5"></i>
                    <span>See Demo</span>
                </span>
            </a>
        @endif
    </div>
</div>
