@extends('layouts.dashboard')

@section('title', 'IoT Sensor Simulator')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="card">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-play-circle text-primary-600"></i>
                Simulation Controls
            </h2>

            <div class="space-y-3">
                <div>
                    <label for="tickAmount" class="block text-sm font-medium text-gray-700 mb-1">Average fill increase per tick (%)</label>
                    <input id="tickAmount" type="number" value="10" min="1" max="50" class="input-field">
                </div>

                <button id="btnTick" type="button" class="btn-primary w-full">
                    <i class="fas fa-forward mr-2"></i> Simulate 1 Hour
                </button>
                <button id="btnReset" type="button" class="btn-secondary w-full">
                    <i class="fas fa-undo mr-2"></i> Reset All Bins to Empty
                </button>

                <div class="pt-3 border-t">
                    <label for="manualBin" class="block text-sm font-medium text-gray-700 mb-1">Set a specific bin reading</label>
                    <select id="manualBin" class="input-field mb-2" @disabled($bins->isEmpty())>
                        @forelse ($bins as $bin)
                            <option value="{{ $bin->bin_code }}">{{ $bin->bin_code }} - {{ $bin->location_name }}</option>
                        @empty
                            <option value="">No active bins</option>
                        @endforelse
                    </select>
                    <div class="flex gap-2">
                        <input id="manualLevel" type="number" min="0" max="100" value="100" class="input-field" placeholder="Fill %" aria-label="Fill level percentage" @disabled($bins->isEmpty())>
                        <button id="btnManual" type="button" class="btn-primary" aria-label="Send sensor reading" @disabled($bins->isEmpty())>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>

            <p id="simulatorMessage" class="text-sm mt-4" role="status" aria-live="polite"></p>
            <div class="mt-4 pt-4 border-t text-xs text-gray-500">
                <p><i class="fas fa-info-circle"></i> Device readings are accepted at <code class="bg-gray-100 px-1 rounded">/api/iot/update</code>.</p>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="card">
            <div class="flex flex-wrap justify-between items-center gap-2 mb-4">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-satellite-dish text-green-500"></i>
                    Live Bin Feed
                </h2>
                <span class="text-xs text-gray-500">Last update: <time id="lastUpdate">never</time></span>
            </div>

            <div id="binGrid" class="space-y-3">
                @forelse ($bins as $bin)
                    <div class="bin-card flex items-center justify-between gap-3 p-4 bg-gray-50 rounded-lg" data-code="{{ $bin->bin_code }}">
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-gray-800">{{ $bin->bin_code }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $bin->location_name }}</p>
                        </div>
                        <div class="w-24 sm:w-48 shrink-0">
                            <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden" role="progressbar" aria-label="{{ $bin->bin_code }} fill level" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $bin->fill_level }}">
                                <div class="fill-bar h-4 transition-all duration-500" style="width: {{ $bin->fill_level }}%; background: {{ $bin->fill_level >= 80 ? '#ef4444' : ($bin->fill_level >= 50 ? '#f59e0b' : '#22c55e') }};"></div>
                            </div>
                        </div>
                        <div class="w-16 shrink-0 text-right">
                            <span class="fill-text font-bold">{{ $bin->fill_level }}%</span>
                            <p class="status-text text-xs capitalize text-gray-500">{{ $bin->status }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-8">No active bins available for simulation.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const messageElement = document.getElementById('simulatorMessage');

    function showMessage(message, isError = false) {
        messageElement.textContent = message;
        messageElement.className = `text-sm mt-4 ${isError ? 'text-red-600' : 'text-green-700'}`;
    }

    async function postJson(url, payload = {}) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(payload),
        });
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'The request could not be completed.');
        }

        return data;
    }

    function updateBinCard(bin) {
        const card = Array.from(document.querySelectorAll('.bin-card')).find((item) => item.dataset.code === bin.bin_code);
        if (!card) return;

        const bar = card.querySelector('.fill-bar');
        const progress = card.querySelector('[role="progressbar"]');
        bar.style.width = `${bin.fill_level}%`;
        bar.style.background = bin.fill_level >= 80 ? '#ef4444' : bin.fill_level >= 50 ? '#f59e0b' : '#22c55e';
        card.querySelector('.fill-text').textContent = `${bin.fill_level}%`;
        card.querySelector('.status-text').textContent = bin.status;
        progress.setAttribute('aria-valuenow', bin.fill_level);
    }

    function markUpdate() {
        document.getElementById('lastUpdate').textContent = new Date().toLocaleTimeString();
    }

    document.getElementById('btnTick').addEventListener('click', async () => {
        try {
            const amount = Number(document.getElementById('tickAmount').value);
            const data = await postJson('{{ route('iot.tick') }}', { amount });
            data.changes.forEach(updateBinCard);
            markUpdate();
            showMessage(`Updated ${data.changes.length} active bin readings.`);
        } catch (error) {
            showMessage(error.message, true);
        }
    });

    document.getElementById('btnReset').addEventListener('click', async () => {
        if (!confirm('Reset all active bins to 0%?')) return;

        try {
            await postJson('{{ route('iot.reset') }}');
            document.querySelectorAll('.bin-card').forEach((card) => {
                updateBinCard({ bin_code: card.dataset.code, fill_level: 0, status: 'empty' });
            });
            markUpdate();
            showMessage('All active bins are now empty.');
        } catch (error) {
            showMessage(error.message, true);
        }
    });

    document.getElementById('btnManual').addEventListener('click', async () => {
        try {
            const binCode = document.getElementById('manualBin').value;
            const fillLevel = Number(document.getElementById('manualLevel').value);
            const data = await postJson('{{ route('iot.update') }}', { bin_code: binCode, fill_level: fillLevel });
            updateBinCard(data);
            markUpdate();
            showMessage(`${data.bin_code} reading set to ${data.fill_level}%.`);
        } catch (error) {
            showMessage(error.message, true);
        }
    });
</script>
@endpush
