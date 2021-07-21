<script src="<?php echo $theme_path;?>/plugins/chart.js/Chart.bundle.min.js"></script>
<script src="<?php echo $theme_path;?>/plugins/chart.js/utils.js"></script>

<style>
	canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
	</style>
    
<div class="row">
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Monthly</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_yearly" ></canvas>
			</div>
		</div>
	</section>
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Company</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_company" ></canvas>
			</div>
		</div>
	</section>
	
</div>

<div class="row">
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Insiden</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_insiden" ></canvas>
			</div>
		</div>
	</section>
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Departemen</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_departemen" ></canvas>
			</div>
		</div>
	</section>
	
</div>

<div class="row">
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Jam</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_jam" ></canvas>
			</div>
		</div>
	</section>
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Shift Kerja</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_shift_kerja" ></canvas>
			</div>
		</div>
	</section>
</div>

<div class="row">
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Hari Kerja</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_hari_kerja" ></canvas>
			</div>
		</div>
	</section>
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Jabatan</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_jabatan" ></canvas>
			</div>
		</div>
	</section>
	
</div>

<div class="row">
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Masa Kerja</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_masa_kerja" ></canvas>
			</div>
		</div>
	</section>
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Umur</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_umur" ></canvas>
			</div>
		</div>
	</section>
</div>

<div class="row">
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Area</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_area" ></canvas> 
			</div>
		</div>
	</section>
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Bulan</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_bulan" ></canvas>
			</div>
		</div>
	</section>
</div>

<div class="row">
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Alat Terlibat</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_alat" ></canvas> 
			</div>
		</div>
	</section>
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Bagian Luka</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_luka" ></canvas>
			</div>
		</div>
	</section>
</div>

<div class="row">
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Cara Kerja Tidak Memenuhi Standar</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_cara_kerja" ></canvas> 
			</div>
		</div>
	</section>
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Kondisi Tidak Memenuhi Standar</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_kondisi" ></canvas>
			</div>
		</div>
	</section>
</div>

<div class="row">
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Faktor Pekerjaan</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_faktor_kerja" ></canvas>
			</div>
		</div>
	</section>
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Faktor Pribadi</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_pribadi" ></canvas>
			</div>
		</div>
	</section>
</div>

<div class="row">
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Tindakan Perbaikan</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style=""> 
				<canvas id="chart_tindakan" ></canvas>
			</div>
		</div>
	</section>
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Sanksi</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_sanksi" ></canvas>
			</div>
		</div>
	</section>
</div>
<div class="row">
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Hari Kerja Hilang</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_lost_workday" ></canvas>
			</div>
		</div>
	</section>
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Biaya Perbaikan Unit</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_biaya_perbaikan" ></canvas> 
			</div>
		</div>
	</section>
</div>
<div class="row">
	<section class="col-lg-6">
		<div class="box box-solid">
			<div class="box-header">
				<i class="fa fa-th"></i>
				<h3 class="box-title">Chart by Kontraktor</h3>
				<div class="box-tools pull-right"></div>
			</div>
			<div class="box-body border-radius-none" style="">
				<canvas id="chart_kontraktor" ></canvas>
			</div>
		</div>
	</section>
</div>

<script>
	new Chart(document.getElementById("chart_yearly"), {
		type: 'bar',
		data: <?php echo $yearly_data;?>,
		options: {
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize:9,
						stepSize: 50,
						max: 500
					}
				}]
			},
			tooltips: {
			callbacks: {
					label: function(t, d) {
						var xLabel = d.datasets[t.datasetIndex].label;
						var yLabel = t.yLabel + '%';
						return xLabel + ': ' + yLabel;
					}
				}
			}

		}
	});
	
	
	new Chart(document.getElementById("chart_company"), {
		type: 'bar',
		data: <?php echo $company_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.yLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});

	
	new Chart(document.getElementById("chart_insiden"), {
		type: 'horizontalBar',
		data: <?php echo $insiden_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});

	new Chart(document.getElementById("chart_departemen"), {
		type: 'horizontalBar',
		data: <?php echo $departemen_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});
	
	
	new Chart(document.getElementById("chart_jam"), {
		type: 'horizontalBar',
		data: <?php echo $jam_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});

	new Chart(document.getElementById("chart_shift_kerja"), {
		type: 'horizontalBar',
		data: <?php echo $shift_kerja_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});

	
	new Chart(document.getElementById("chart_hari_kerja"), {
		type: 'horizontalBar',
		data: <?php echo $hari_kerja_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});
	
	
	new Chart(document.getElementById("chart_jabatan"), {
		type: 'horizontalBar',
		data: <?php echo $jabatan_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});

	
	new Chart(document.getElementById("chart_masa_kerja"), {
		type: 'horizontalBar',
		data: <?php echo $masa_kerja_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});

	new Chart(document.getElementById("chart_umur"), {
		type: 'horizontalBar',
		data: <?php echo $umur_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});

	new Chart(document.getElementById("chart_area"), {
		type: 'horizontalBar',
		data: <?php echo $area_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});

	new Chart(document.getElementById("chart_bulan"), {
		type: 'horizontalBar',
		data: <?php echo $bulan_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});

	new Chart(document.getElementById("chart_alat"), {
		type: 'horizontalBar',
		data: <?php echo $alat_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});

	new Chart(document.getElementById("chart_luka"), {
		type: 'horizontalBar',
		data: <?php echo $luka_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});

	new Chart(document.getElementById("chart_cara_kerja"), {
		type: 'horizontalBar',
		data: <?php echo $cara_kerja_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});

	new Chart(document.getElementById("chart_kondisi"), {
		type: 'horizontalBar',
		data: <?php echo $kondisi_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});
	new Chart(document.getElementById("chart_faktor_kerja"), {
		type: 'horizontalBar',
		data: <?php echo $fkerja_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});
	new Chart(document.getElementById("chart_pribadi"), {
		type: 'horizontalBar',
		data: <?php echo $fpribadi_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});
	new Chart(document.getElementById("chart_tindakan"), {
		type: 'horizontalBar',
		data: <?php echo $tindakan_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});
	new Chart(document.getElementById("chart_sanksi"), {
		type: 'horizontalBar',
		data: <?php echo $sanksi_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});
	new Chart(document.getElementById("chart_lost_workday"), {
		type: 'horizontalBar',
		data: <?php echo $hilang_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});
	new Chart(document.getElementById("chart_biaya_perbaikan"), {
		type: 'horizontalBar',
		data: <?php echo $biaya_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});
	new Chart(document.getElementById("chart_kontraktor"), {
		type: 'horizontalBar',
		data: <?php echo $kontraktor_data;?>,
		options: {
			scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							fontSize:9,
						}
					}]
				},
		legend: { display: false },
		tooltips: {
			callbacks: {
				label: function(t, d) {
					var xLabel = d.datasets[t.datasetIndex].label;
					var yLabel = t.xLabel + '%';
					return xLabel + ': ' + yLabel;
				}
			}
			}
		}
	});
	
</script>