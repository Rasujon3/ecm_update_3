<div class="col-md-4" style="">
    <div class="card border-0 shadow-sm p-3" style="background-color: #f8f9fa; border-radius: 10px; min-height: 500px !important;">
        <!-- Badge -->
        @if($plan->sub_title)
            <span class="badge bg-purple text-white position-absolute top-0 start-50 translate-middle" style="font-size: 0.9rem; padding: 5px 15px; border-radius: 20px;">
            {{ $plan->sub_title }}
            </span>
        @endif

        <div class="card-body text-center pt-5">
            <!-- Title -->
            <h4 class="card-title text-purple fw-bold mb-1" style="font-size: 1.5rem;">
                {{ $plan->package_name ?? '' }}
            </h4>

            <!-- Subtitle -->
            <p class="card-text text-muted text-left mb-2" style="font-size: 0.9rem;">
                {{ $plan->short_description ?? '' }}
            </p>

            <!-- Price -->
            <h2 class="card-title text-purple fw-bold mb-4" style="font-size: 2.5rem;">
                ৳ {{ $plan->price ?? '' }}
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
                        <div class="row">
                            <svg stroke="currentColor" fill="currentColor" stroke-width="0" version="1.2" baseProfile="tiny" viewBox="0 0 24 24" class="text-green-600 text-lg" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M16.972 6.251c-.967-.538-2.185-.188-2.72.777l-3.713 6.682-2.125-2.125c-.781-.781-2.047-.781-2.828 0-.781.781-.781 2.047 0 2.828l4 4c.378.379.888.587 1.414.587l.277-.02c.621-.087 1.166-.46 1.471-1.009l5-9c.537-.966.189-2.183-.776-2.72z"></path></svg>
                            <li class="d-flex align-items-center justify-content-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                {{ $service->title ?? '' }}
                            </li>
                        </div>
                    @endforeach
                </ul>
            @endif

            <!-- See Demo Button -->
            @if($plan->demo_url)
                <a href="{{ $plan->demo_url }}" target="_blank" class="btn btn-success w-100 text-white" style="font-size: 1rem; padding: 10px; border-radius: 5px;">
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
