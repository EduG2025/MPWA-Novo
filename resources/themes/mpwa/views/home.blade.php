<x-layout-dashboard title="{{__('Home')}}">
			@if (session()->has('alert'))
				<x-alert>
					@slot('type', session('alert')['type'])
					@slot('msg', session('alert')['msg'])
				</x-alert>
			@endif
			@if ($errors->any())
				<div class="alert alert-danger alert-dismissible" role="alert">
					<h4 class="alert-heading d-flex align-items-center">
						<span class="alert-icon rounded">
							<i class="icon-base ti tabler-face-id-error icon-md"></i>
						</span>
						{{__('Oh Error :(')}}
					</h4>
					<hr>
					<p class="mb-0">
						<p>{{__('The given data was invalid.')}}</p>
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</p>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			@endif
				@if ($newVersion)
                    <div class="alert alert-danger">
                        {{__('A new version is available:')}} <span class="text-danger">v{{ $newVersion }}</span> <a href="{{ route('update') }}">{{__('Click Here')}}</a></ul>
                    </div>
                @endif
              <div class="row g-6">
                <!-- Website Analytics -->
                <div class="col-xl-6 col">
                  <div
                    class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg"
                    id="swiper-with-pagination-cards">
                    <div class="swiper-wrapper">
                      <div class="swiper-slide">
                        <div class="row">
                          <div class="col-12">
                            <h5 class="text-white mb-0">{{__('Blast/Bulk')}}</h5>
							@php
							  $totalMessages = $user->blasts_success + $user->blasts_failed + $user->blasts_pending;
							  $successRate = $totalMessages > 0 ? round(($user->blasts_success / $totalMessages) * 100, 2) : 0;
							@endphp
                            <small>{{ __('Total :successRate% successful message rate', ['successRate' => $successRate]) }}</small>
                          </div>
                          <div class="row">
                            <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1 pt-md-9">
                              <h6 class="text-white mt-0 mt-md-3 mb-4">{{__('Messages')}}</h6>
                              <div class="row">
                                <div class="col-6">
                                  <ul class="list-unstyled mb-0">
                                    <li class="d-flex mb-4 align-items-center">
                                      <p class="mb-0 fw-medium me-2 website-analytics-text-bg">{{ number_format($user->blasts_pending) }}</p>
                                      <p class="mb-0">{{__('Wait')}}</p>
                                    </li>
                                    <li class="d-flex align-items-center">
                                      <p class="mb-0 fw-medium me-2 website-analytics-text-bg">{{ number_format($user->blasts_success) }}</p>
                                      <p class="mb-0">{{__('Sent')}}</p>
                                    </li>
                                  </ul>
                                </div>
                                <div class="col-6">
                                  <ul class="list-unstyled mb-0">
                                    <li class="d-flex mb-4 align-items-center">
                                      <p class="mb-0 fw-medium me-2 website-analytics-text-bg">{{ number_format($user->blasts_failed) }}</p>
                                      <p class="mb-0">{{__('Fail')}}</p>
                                    </li>
                                    <li class="d-flex align-items-center">
                                      <p class="mb-0 fw-medium me-2 website-analytics-text-bg">{{number_format($user->campaigns_count)}}</p>
                                      <p class="mb-0">{{__('Campaigns')}}</p>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
							  <i class="icon-base ti tabler-brand-whatsapp card-website-analytics-img" style="width:150px; height:150px;"></i>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-pagination"></div>
                  </div>
                </div>
                <!--/ Website Analytics -->

                <!-- Average Daily Sales -->
                <div class="col-xl-3 col-sm-6">
                  <div class="card h-100">
                    <div class="card-header pb-0">
                      <h5 class="mb-3 card-title">{{__('All Messages Sent')}}</h5>
                      <h4 class="mb-0">{{ number_format($user->message_histories_count) }}</h4>
					  <p class="mb-0 text-body">{{ __('Remain: (:remain) Messages', [ 'remain' => $user->level === 'admin' ? '∞' : number_format($user->plan_data['messages_limit'] ?? 0) ]) }}</p>
                    </div>
                    <div class="card-body px-0">
                      <div id="averageDailySales"></div>
                    </div>
                  </div>
                </div>
                <!--/ Average Daily Sales -->

                <!-- Sales Overview -->
				@php
				  $totalLimit = $user->limit_device;
				  $used = $user->devices_count;
				  $remain = max($totalLimit - $used, 0);
				  $usedPercent = $totalLimit > 0 ? round(($used / $totalLimit) * 100, 1) : 0;
				  $remainPercent = 100 - $usedPercent;
				@endphp
                <div class="col-xl-3 col-sm-6">
                  <div class="card h-100">
                    <div class="card-header">
                      <div class="d-flex justify-content-between">
                        <p class="mb-0 text-body">{{__('Total Devices')}}</p>
                      </div>
                      <h4 class="card-title mb-1">{{ $used }}</h4>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="d-flex gap-2 align-items-center mb-2">
                            <span class="badge bg-label-info p-1 rounded">
								<i class="icon-base ti tabler-activity icon-sm"></i>
							</span>
                            <p class="mb-0">{{__('Limit')}}</p>
                          </div>
                          <h5 class="mb-0 pt-1">{{ $usedPercent }}%</h5>
                          <small class="text-body-secondary">{{ $totalLimit }}</small>
                        </div>
                        <div class="col-4">
                          <div class="divider divider-vertical">
                            <div class="divider-text">
                              <span class="badge-divider-bg bg-label-secondary">VS</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-4 text-end">
                          <div class="d-flex gap-2 justify-content-end align-items-center mb-2">
                            <p class="mb-0">{{__('Remain')}}</p>
                            <span class="badge bg-label-primary p-1 rounded"
                              ><i class="icon-base ti tabler-trending-up icon-sm"></i
                            ></span>
                          </div>
                          <h5 class="mb-0 pt-1">{{ $remainPercent }}%</h5>
                          <small class="text-body-secondary">{{ $remain }}</small>
                        </div>
                      </div>
                      <div class="d-flex align-items-center mt-6">
                        <div class="progress w-100" style="height: 10px">
						  <div
							class="progress-bar bg-info"
							style="width: {{ $usedPercent }}%"
							role="progressbar"
							aria-valuenow="{{ $usedPercent }}"
							aria-valuemin="0"
							aria-valuemax="100"></div>
						  <div
							class="progress-bar bg-primary"
							role="progressbar"
							style="width: {{ $remainPercent }}%"
							aria-valuenow="{{ $remainPercent }}"
							aria-valuemin="0"
							aria-valuemax="100"></div>
						</div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Sales Overview -->
				@php
  $diskTotal     = disk_total_space('/');
  $diskFree      = disk_free_space('/');
  $diskUsed      = $diskTotal - $diskFree;
  $diskTotalGB   = number_format($diskTotal/1024/1024/1024,1);
  $diskUsedGB    = number_format($diskUsed/1024/1024/1024,1);
  $diskPercent   = round($diskUsed / $diskTotal * 100);
  $phpVersion    = phpversion();
  $mysqlVersion  = DB::selectOne('SELECT VERSION() AS version')->version;
  $laravelVersion = Illuminate\Foundation\Application::VERSION;
@endphp

  <!-- System Resources -->
  <div class="col-md-6">
    <div class="row gx-4 gy-4">
      <!-- System Resources -->
      <div class="col-12">
        <div class="card h-100">
          <div class="card-header pb-0">
            <h5 class="mb-1">{{__('System Resources')}}</h5>
            <small class="text-muted">{{__('Live Memory & Disk')}}</small>
          </div>
          <div class="card-body">
            <div class="row g-4">
              <!-- RAM -->
              <div class="col-12 col-sm-6">
                <div class="d-flex align-items-center mb-2">
                  <div class="bg-label-primary rounded p-2 me-3">
                    <i class="icon-base ti tabler-device-sd-card fs-3"></i>
                  </div>
                  <div class="w-100">
                    <h5 class="mb-1">
                      <span id="ramUsed">--</span> / <span id="ramTotal">--</span> {{__('MB')}}
                    </h5>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                      <span>{{__('RAM Usage')}}</span>
                      <span><span id="ramPercent">--</span>%</span>
                    </div>
                    <div class="progress" style="height:6px">
                      <div id="ramProgressBar" class="progress-bar bg-primary" style="width:0%"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mt-2">
                      <span>{{__('Free RAM')}}</span>
                      <span><span id="ramFree">--</span> {{__('MB')}}</span>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Disk -->
              <div class="col-12 col-sm-6">
                <div class="d-flex align-items-center">
                  <div class="bg-label-info rounded p-2 me-3">
                    <i class="icon-base ti tabler-stack-2 fs-3"></i>
                  </div>
                  <div class="w-100">
                    <h5 class="mb-1">{{ $diskUsedGB }} / {{ $diskTotalGB }} {{__('GB')}}</h5>
                    <div class="d-flex justify-content-between small text-muted mb-1">
                      <span>{{__('Disk Usage')}}</span>
                      <span>{{ $diskPercent }}%</span>
                    </div>
                    <div class="progress" style="height:6px">
                      <div class="progress-bar bg-info" style="width:{{ $diskPercent }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mt-2">
                      <span>{{__('Free Disk')}}</span>
                      <span>{{ number_format($diskFree/1024/1024/1024,1) }} {{__('GB')}}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Server Info -->
      <div class="col-12">
        <div class="card h-100">
          <div class="card-header pb-0">
            <h5 class="mb-1">{{__('Server Info')}}</h5>
            <small class="text-muted">{{__('Environment Versions')}}</small>
          </div>
          <div class="card-body">
            <div class="row gy-3">
              <div class="col-12 col-sm-4 d-flex align-items-center">
                <div class="bg-label-warning rounded p-2 me-3">
                  <i class="icon-base ti tabler-brand-php fs-3"></i>
                </div>
                <div>
                  <h6 class="mb-1">{{__('PHP')}}</h6>
                  <small class="text-muted">{{ $phpVersion }}</small>
                </div>
              </div>
              <div class="col-12 col-sm-4 d-flex align-items-center">
                <div class="bg-label-success rounded p-2 me-3">
                  <i class="icon-base ti tabler-server fs-3"></i>
                </div>
                <div>
                  <h6 class="mb-1">{{__('Laravel')}}</h6>
                  <small class="text-muted">{{ $laravelVersion }}</small>
                </div>
              </div>
              <div class="col-12 col-sm-4 d-flex align-items-center">
                <div class="bg-label-danger rounded p-2 me-3">
                  <i class="icon-base ti tabler-database fs-3"></i>
                </div>
                <div>
                  <h6 class="mb-1">{{__('MySQL')}}</h6>
                  <small class="text-muted">{{ $mysqlVersion }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>        
  </div>

  <!-- CPU Usage (unchanged) -->
  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-header pb-0">
        <h5 class="mb-1">{{__('CPU Usage')}}</h5>
        <small class="text-muted">{{__('Real-time Processor Load')}}</small>
      </div>
      <div class="card-body d-flex flex-column align-items-center">
		  <div id="cpuusage"></div>
		  <div class="mt-3 text-center">
			<small id="cpuInfo" class="text-muted">--</small>
		  </div>
	  </div>
    </div>
  </div>
</div>

<script src="https://cdn.socket.io/4.8.1/socket.io.min.js" crossorigin="anonymous"></script>
<script>
  if ('{{ env('TYPE_SERVER') }}' === 'hosting') {
	socket = io();
  } else {
	socket = io('{{ env("WA_URL_SERVER") }}', {
        transports: ['websocket', 'polling', 'flashsocket']
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    const cpuEl = document.getElementById('cpuusage');
    const cpuChart = new ApexCharts(cpuEl, {
      series: [0],
	  labels: ['CPU'],
      chart: { type: 'radialBar', height: 300 },
      plotOptions: {
        radialBar: {
          hollow: { size: '65%' },
          startAngle: -140,
          endAngle: 140,
          track: { background: '#f0f0f0', strokeWidth: '100%' },
          dataLabels: {
            name: {
              offsetY: -20,
              color: config.colors.textMuted,
              fontSize: '13px',
              fontWeight: '400'
            },
            value: {
              offsetY: 10,
              color: config.colors.headingColor,
              fontSize: '38px',
              fontWeight: '400'
            }
          }
        }
      },
      colors: [config.colors.primary]
    });
    cpuChart.render();

    socket.on('serverStats', data => {
      const used = parseInt(data.ram_used);
       const total = parseInt(data.ram_total);
			const free  = data.ram_free;
            const pct   = Math.round(used / total * 100);
			const model = data.cpu_model;
		  const cores = data.cpu_name;
		  document.getElementById('ramUsed').innerText  = used.toLocaleString();
		  document.getElementById('ramTotal').innerText = total.toLocaleString();
		  document.getElementById('ramFree').innerText   = free.toLocaleString();
		  document.getElementById('ramPercent').innerText = pct;
		  document.getElementById('ramProgressBar').style.width = pct + '%';
		  document.getElementById('cpuInfo').innerText = `${model} ${cores} Core`;

      cpuChart.updateSeries([parseFloat(data.cpu)]);
    });
  });
</script>

</x-layout-dashboard>