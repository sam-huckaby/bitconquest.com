<?php

use App\Models\Domain;
use App\Services\DnsRecordService;
use App\Services\DomainColorService;
use App\Services\DomainScoreService;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component
{
    protected $dnsRecordService;
    protected $domainColorService;
    protected $domainScoreService;

    public function mount(DnsRecordService $dnsRecordService, DomainColorService $domainColorService, DomainScoreService $domainScoreService)
    {
        $this->dnsRecordService = $dnsRecordService;
        $this->domainColorService = $domainColorService;
        $this->domainScoreService = $domainScoreService;
    }

    private function hslToRgb($h, $s, $l)
    {
        $s /= 100;
        $l /= 100;

        $c = (1 - abs(2 * $l - 1)) * $s;
        $x = $c * (1 - abs(fmod(($h / 60), 2) - 1));
        $m = $l - $c / 2;

        if ($h < 60) {
            $r = $c;
            $g = $x;
            $b = 0;
        } else if ($h < 120) {
            $r = $x;
            $g = $c;
            $b = 0;
        } else if ($h < 180) {
            $r = 0;
            $g = $c;
            $b = $x;
        } else if ($h < 240) {
            $r = 0;
            $g = $x;
            $b = $c;
        } else if ($h < 300) {
            $r = $x;
            $g = 0;
            $b = $c;
        } else {
            $r = $c;
            $g = 0;
            $b = $x;
        }

        $r = (int) (($r + $m) * 255);
        $g = (int) (($g + $m) * 255);
        $b = (int) (($b + $m) * 255);

        return array($r, $g, $b);
    }

    private function calculateColor($number)
    {
        if ($number < 0 || $number > 25) {
            throw new InvalidArgumentException("Number must be between 0 and 25.");
        }

        // Map the number to a hue value between 0° and 360°
        $hue = ($number * (360 / 26));

        // Assuming full saturation and half lightness for vibrant colors
        $saturation = 100;
        $lightness = 50;

        // Convert HSL to RGB, as GD library in PHP uses RGB
        return $this->hslToRgb($hue, $saturation, $lightness);
    }

    // Determine the positioning of the particle
    private function calculateParticlePosition($letter_pair, $img_w, $img_h)
    {
        $alphabet = range('a', 'z');

        // Find positions in the alphabet (1-26)
        $pos1 = array_search($letter_pair[0], $alphabet) + 1;
        $pos2 = array_search($letter_pair[1] ?? $letter_pair[0], $alphabet) + 1;

        // Calculate percentages through the alphabet
        $percentX = $pos1 / count($alphabet);
        $percentY = $pos2 / count($alphabet);

        // Calculate positions relative to image dimensions
        $x = $percentX * $img_w;
        $y = $percentY * $img_h;

        return [$x, $y];
    }

    // Determine the shape, color, and size of the particle
    private function calculateParticleAttributes($letter_pair)
    {
        $alphabet = range('a', 'z');

        // Find positions in the alphabet (0-25)
        $pos1 = array_search($letter_pair[0], $alphabet);
        $pos2 = array_search($letter_pair[1] ?? $letter_pair[0], $alphabet);

        $radius = 5 + (5 * max([$pos1, $pos2]) - min([$pos1, $pos2]));
        $color = $this->calculateColor($pos1);

        switch ($pos2 % 5) {
            case 0:
                $shape = 'circle';
                break;
            case 1:
                $shape = 'square';
                break;
            case 2:
                $shape = 'triangle';
                break;
            case 3:
                $shape = 'pentagon';
                break;
            case 4:
                $shape = 'hexagon';
                break;
            case 5:
                $shape = 'star';
                break;
        }

        return [$radius, $color, $shape];
    }

    private function drawParticle($image, $x, $y, $radius, $color, $shape, $filled)
    {
        // Convert the RGB array to a color identifier
        $colorIdentifier = imagecolorallocate($image, $color[0], $color[1], $color[2]);

        switch ($shape) {
            case "circle":
                if ($filled) {
                    imagefilledellipse($image, $x, $y, $radius * 2, $radius * 2, $colorIdentifier);
                } else {
                    imageellipse($image, $x, $y, $radius * 2, $radius * 2, $colorIdentifier);
                }
                break;
            case "square":
                if ($filled) {
                    imagefilledrectangle($image, $x - $radius, $y - $radius, $x + $radius, $y + $radius, $colorIdentifier);
                } else {
                    imagerectangle($image, $x - $radius, $y - $radius, $x + $radius, $y + $radius, $colorIdentifier);
                }
                break;
            case "triangle":
                $this->drawPolygon($image, $x, $y, $radius, 3, $colorIdentifier, $filled);
                break;
            case "pentagon":
                $this->drawPolygon($image, $x, $y, $radius, 5, $colorIdentifier, $filled);
                break;
            case "hexagon":
                $this->drawPolygon($image, $x, $y, $radius, 6, $colorIdentifier, $filled);
                break;
            case "star":
                $this->drawStar($image, $x, $y, $radius, $colorIdentifier, $filled);
                break;
            default:
                throw new InvalidArgumentException("Unsupported shape: $shape");
        }
    }

    private function drawPolygon($image, $x, $y, $radius, $sides, $color, $filled)
    {
        $points = [];
        for ($i = 0; $i < $sides; $i++) {
            $points[] = $x + $radius * cos($i * 2 * M_PI / $sides);
            $points[] = $y + $radius * sin($i * 2 * M_PI / $sides);
        }
        if ($filled) {
            imagefilledpolygon($image, $points, $sides, $color);
        } else {
            imagepolygon($image, $points, $sides, $color);
        }
    }

    private function drawStar($image, $x, $y, $radius, $color, $filled)
    {
        $points = [];
        $density = 5; // Number of points (5 for a standard star)
        for ($i = 0; $i <= $density * 2; $i++) {
            $angle = M_PI * $i / $density;
            $r = $i % 2 === 0 ? $radius : $radius / 2;
            $points[] = $x + $r * cos($angle);
            $points[] = $y + $r * sin($angle);
        }
        if ($filled) {
            imagefilledpolygon($image, $points, count($points) / 2, $color);
        } else {
            imagepolygon($image, $points, count($points) / 2, $color);
        }
    }


    private function createBase64Image($inc_hostname, $inc_tld)
    {
        // Convert letters to lowercase for consistency
        $hostname = strtolower($inc_hostname);
        $tld = strtolower($inc_tld);

        $width = 300;
        $height = 150;
        // Create a 300x150 image
        $image = imagecreatetruecolor($width, $height);
        imagesetthickness($image, 5);

        $this->domainColorService = app(DomainColorService::class);
        $background = $this->domainColorService->tldToRgb($tld);

        // Set background color to white
        $backgroundColor = imagecolorallocate($image, $background[0], $background[1], $background[2]);
        imagefill($image, 0, 0, $backgroundColor);

        $pairs = str_split($hostname, 2);
        $lastIndex = count($pairs) - 1;
        foreach ($pairs as $index => $pair) {
            // Calculate positions and colors
            list($x, $y) = $this->calculateParticlePosition($pair, $width, $height);
            list($radius, $color, $shape) = $this->calculateParticleAttributes($pair);

            $filled = $index !== $lastIndex || (strlen($hostname) % 2 === 0);

            // Draw shape
            $this->drawParticle($image, $x, $y, $radius, $color, $shape, $filled);
        }

        // =================================================================
        // Stamp the domain name on the flair, for extra uniqueness
        // =================================================================
        // Set the text color to black
        $textColor = imagecolorallocate($image, 0, 0, 0);
        // Add the text to the image
        imagestring($image, 5, 5, 3, ($hostname . "." . $tld), $textColor);
        // =================================================================


        // Start output buffering
        ob_start();
        imagepng($image);
        $imageData = ob_get_contents();
        ob_end_clean();
        // Free up memory
        imagedestroy($image);

        // Return the base64-encoded image
        return base64_encode($imageData);
    }

    #[Validate('required|string|max:255|regex:/^[a-zA-Z0-9-]+$/')]
    public string $hostname = '';

    #[Validate('required|string|max:255|regex:/^[a-zA-Z]{2,}$/')]
    public string $tld = '';

    #[Validate('required')]
    public int $score = 0;

    public string $flair = '';

    #[Validate('required')]
    public bool $verified = false;

    public $newDomain;

    public function store(): void
    {
        $validated = $this->validate();

        $alreadyCollected = auth()->user()->currentTeam->domains()
            ->where('hostname', $this->hostname)
            ->where('tld', $this->tld)
            ->exists();

        if ($alreadyCollected) {
            $this->addError('hostname', 'You\'ve already collected this domain!');
            return;
        }

        // Instantiate the service, because mount() isn't a true constructor
        // Discovered here: https://laracasts.com/discuss/channels/livewire/calling-function-in-services-class-is-not-working
        $this->dnsRecordService = app(DnsRecordService::class);
        $teamVerificationKey = auth()->user()->currentTeam->collection_key;

        // Add additional properties to the validated data
        $validated['flair'] = $this->createBase64Image($this->hostname, $this->tld);
        $validated['score'] = $this->score;
        $validated['verified'] = $this->dnsRecordService->checkDnsTxtRecord($this->hostname . '.' . $this->tld, $teamVerificationKey);

        try {
            $this->newDomain = auth()->user()->currentTeam->domains()->create($validated);
        } catch (Exception $e) {
            // Just swallow any possible errors for now (I don't think any exist)
        }

        // Let's score this sucker
        $this->domainScoreService = app(DomainScoreService::class);
        $this->domainScoreService->score($this->newDomain->id);

        $this->dispatch('show-modal');

        $this->hostname = '';
        $this->tld = '';

        $this->dispatch('domain-collected');
    }
}; ?>

<!-- TODO: May not need the hide-modal event after all -->
<div x-data="{ open: false }" x-cloak x-on:show-modal.window="open = true" x-on:hide-modal.window="open = false">
    <!-- Collection modal -->
    @if ($newDomain)
    <!-- Learn more about x-cloak here: https://alpinejs.dev/directives/cloak -->
    <div x-cloak x-show="open" class="fixed inset-0 bg-gray-600 flex items-center justify-center z-30 glamour-modal">
        <div class="relative bg-white dark:bg-gray-900 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full" x-data="{ collecting: true }">
            <div x-show="collecting" x-on:click="collecting = false" class="absolute top-0 right-0 bottom-0 left-0 flex flex-row justify-center items-center bg-gray-800 text-neutral-200"><span class="p-4 border border-neutral-200 border-solid">Collect</span></div>
            <div class="flex flex-col justify-center items-center p-5">
                <h2 class="text-4xl font-bold dark:text-neutral-200 pb-2">{{ $newDomain->hostname }}.{{ $newDomain->tld }}</h2>
                <img width="300" height="150" src="data:image/png;base64,{{ $newDomain->flair }}" alt="Flair for {{ $newDomain->hostname }}.{{ $newDomain->tld }}" />
            </div>
            <div class="flex flex-row justify-center items-center p-4 border-t border-gray-600">
                <button x-on:click="open = false; collecting = true" class="border border-solid dark:border-neutral-200 text-white font-bold py-2 px-4 rounded">Close</button>
            </div>
        </div>
    </div>
    @endif
    <form wire:submit="store" class="flex flex-row justify-center items-start">
        <div class="flex flex-col justify-start items-start grow mt-4 ml-4">
            <input type="text" wire:model="hostname" placeholder="{{ __('yourdomain') }}" class="block w-full text-right border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
            @error('hostname') <span class="mt-4 text-red-600">{{ $message }}</span> @enderror
        </div>
        <span class="mt-5 inline-block font-bold text-2xl text-neutral-800 dark:text-white align-text-bottom px-2">.</span>
        <div class="flex flex-col justify-start items-start mt-4 w-[350px]">
            <input type="text" wire:model="tld" placeholder="{{ __('com') }}" class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
            @error('tld') <span class="mt-4 text-red-600">{{ $message }}</span> @enderror
        </div>
        <x-button class="m-4 h-[42px]">{{ __('Collect') }}</x-button>
    </form>
</div>
