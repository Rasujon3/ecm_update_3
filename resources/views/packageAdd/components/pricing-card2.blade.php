<div class="col-md-4">
    <div class="card border-0 shadow-sm p-3" style="background-color: #f8f9fa; border-radius: 10px;">
        <!-- Badge -->
        @if($plan->sub_title)
            <span class="badge bg-purple text-white position-absolute top-0 start-50 translate-middle" style="font-size: 0.9rem; padding: 5px 15px; border-radius: 20px;">
            {{ $plan->sub_title }}
            </span>
        @endif

        <div class="card-body text-center pt-5">
            <!-- Title -->
            <h4 class="card-title text-purple fw-bold mb-1" style="font-size: 1.5rem;">
                {{ $plan->package_name }}
            </h4>

            <!-- Subtitle -->
            <p class="card-text text-muted mb-2" style="font-size: 0.9rem;">
                {{ $plan->short_description }}
            </p>

            <!-- Price -->
            <h2 class="card-title text-purple fw-bold mb-4" style="font-size: 2.5rem;">
                ৳ {{ $plan->price }}
            </h2>

            <!-- Order Button -->
            <button
                type="submit"
                name="package_id"
                value="{{ $plan->id }}"
                class="btn btn-purple text-white w-100 mb-4 start-plan-btn"
                style="font-size: 1.1rem; padding: 10px 20px; border-radius: 25px;">
                Start Plan
            </button>


            <!-- Features List -->
            @if(count($plan->services) > 0)
                <ul class="list-unstyled mb-4">
                    @foreach($plan->services as $service)
                        <li class="d-flex align-items-center justify-content-center mb-2">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            {{ $service->title ?? '' }}
                        </li>
                    @endforeach

                </ul>
            @endif

            <!-- See Demo Button -->
            @if($plan->demo_url)
                <a href="{{ $plan->demo_url }}" class="btn btn-success w-100 text-white" style="font-size: 1rem; padding: 10px; border-radius: 5px;">
                    <i class="bi bi-eye me-2"></i> See Demo
                </a>
            @endif
        </div>
    </div>
</div>

<style>
    .btn-purple {
        background-color: #6f42c1;
        border-color: #6f42c1;
    }
    .btn-purple:hover {
        background-color: #5a328c;
        border-color: #5a328c;
    }
    .text-purple {
        color: #6f42c1;
    }
</style>
