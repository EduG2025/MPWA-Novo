<x-layout-dashboard title="{{ __('Troubleshoot') }}">
<style>
	.terminal-line.success { color: #00ff7f; }
	.terminal-line.error { color: #ff4d4f; }
	.terminal-line.warning { color: #ffc107; }
	.terminal-line.info { color: #36e4ff; }
	#terminal {
		height: 350px;
		overflow-y: auto;
		font-family: monospace;
		white-space: pre-wrap;
	}
</style>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('Troubleshoot') }}</h5>
            <button id="startCheck" class="btn btn-sm btn-primary">
                <i class="ti tabler-play me-1"></i> {{ __('Start') }}
            </button>
        </div>
        <div class="card-body">
			<div id="terminal" class="bg-dark text-white p-3 rounded"></div>

			<button id="copyReportBtn" class="btn btn-sm btn-secondary mt-3 d-none">
				<i class="ti tabler-upload me-1"></i> {{ __('Copy Report Link') }}
			</button>
		</div>
    </div>

    <script>
		const terminal = document.getElementById('terminal');
		const copyBtn = document.getElementById('copyReportBtn');
		const startBtn = document.getElementById('startCheck');
		
		let stopRequested = false;
		let reportText = '';

		const checks = [
			'cron-user-history',
			'cron-blast',
			'cron-schedule',
			'php-version',
			'permissions',
			'storage-link',
			'hosting',
			'extensions',
			'ssl',
			'pem-ssl',
			'server',
			'node',
			'curl-test',
			'.env',
			'database',
			'cron'
		];

		async function runCheck(type) {
			if (stopRequested) return;

			const res = await fetch("{{ route('admin.troubleshoot.check') }}", {
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': '{{ csrf_token() }}',
					'Content-Type': 'application/json'
				},
				body: JSON.stringify({ type })
			});

			const data = await res.json();

			for (let line of data) {
				if (stopRequested) return;
				const el = document.createElement('div');
				el.className = 'terminal-line ' + line.status;
				el.textContent = line.text;
				terminal.appendChild(el);
				terminal.scrollTop = terminal.scrollHeight;
				reportText += line.text + '\n';
				await new Promise(resolve => setTimeout(resolve, 100));
			}
			reportText += '\n';
			terminal.innerHTML += '\n';
		}

		async function runAllChecks() {
			terminal.innerHTML = '';
			terminal.innerHTML = '<span class="terminal-line info">{{ __("Initializing system diagnostic...") }}</span>\n\n';
			reportText = '';
			copyBtn.classList.add('d-none');
			stopRequested = false;
			startBtn.innerHTML = '<i class="ti tabler-square me-1"></i> {{ __("Stop") }}';
			startBtn.dataset.running = "1";

			const startTime = performance.now();

			for (let type of checks) {
				if (stopRequested) break;
				await runCheck(type);
			}

			const endTime = performance.now();
			const seconds = ((endTime - startTime) / 1000).toFixed(2);
			const el = document.createElement('div');
			el.className = 'terminal-line info';
			el.textContent = "{{ __('Total time: :seconds seconds') }}".replace(':seconds', seconds);
			terminal.appendChild(el);
			terminal.scrollTop = terminal.scrollHeight;
			reportText += "{{ __('Total time: :seconds seconds') }}\n".replace(':seconds', seconds);

			if (!stopRequested) {
				copyBtn.classList.remove('d-none');
				copyBtn.dataset.report = reportText;
				startBtn.innerHTML = '<i class="ti tabler-play me-1"></i> {{ __("Start") }}';
				startBtn.dataset.running = "0";
			}
		}

		startBtn.addEventListener('click', function () {
			if (startBtn.dataset.running === "1") {
				stopRequested = true;
				startBtn.innerHTML = '<i class="ti tabler-play me-1"></i> {{ __("Start") }}';
				startBtn.dataset.running = "0";
			} else {
				runAllChecks();
			}
		});

		copyBtn.addEventListener('click', async function () {
			const originalText = copyBtn.innerHTML;
			copyBtn.disabled = true;
			copyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> {{ __("Uploading...") }}';

			try {
				const res = await fetch("{{ route('admin.troubleshoot.upload') }}", {
					method: 'POST',
					headers: {
						'X-CSRF-TOKEN': '{{ csrf_token() }}',
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({ report: copyBtn.dataset.report })
				});

				const result = await res.json();

				if (res.ok && result.link) {
					await navigator.clipboard.writeText(result.link);
					copyBtn.innerHTML = '{{ __("Link copied to clipboard!") }}';
					notyf.success('{{ __("Link copied successfully!") }}');
				} else {
					copyBtn.innerHTML = '{{ __("Upload failed!") }}';
					notyf.error('{{ __("Pastebin upload failed.") }}');
				}
			} catch (e) {
				copyBtn.innerHTML = '{{ __("Failed to upload!") }}';
				notyf.error('{{ __("Unexpected error during upload.") }}');
			}

			setTimeout(() => {
				copyBtn.innerHTML = originalText;
				copyBtn.disabled = false;
			}, 3000);
		});
	</script>
</x-layout-dashboard>
