@extends('adminlte::page')

@section('title', 'Gráficos de Avaliações')

@section('content_header')
{{-- Usamos o nome do aluno no título para deixar mais dinâmico --}}
<h1 class="text-bold"><i class="fas fa-chart-bar"></i> Gráficos de Evolução: {{ $aluno->name }}</h1>
@stop

@section('content')
<div class="card">
   <div class="card-header border-0">
      <h3 class="card-title">
         <i class="fas fa-th mr-1"></i>
         Evolução das Métricas
      </h3>
   </div>
   <div class="card-body">
      <canvas id="peso" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      <canvas id="altura" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      <canvas id="imc" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      <canvas id="gordura" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      <canvas id="massaMuscular" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      <canvas id="circunferencia_cintura" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      <canvas id="circunferencia_quadril" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      <canvas id="circunferencia_braco_relaxado" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      <canvas id="circunferencia_braco_contraido" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      <canvas id="circunferencia_peito" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      <canvas id="circunferencia_coxa" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
      <canvas id="circunferencia_panturrilha" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
   </div>
</div>
@stop

@section('js')

<script>
   $(function() {
      'use strict'

      const ctx = document.getElementById('peso').getContext('2d');
      const ctx2 = document.getElementById('altura').getContext('2d');
      const ctx3 = document.getElementById('imc').getContext('2d');
      const ctx4 = document.getElementById('gordura').getContext('2d');
      const ctx5 = document.getElementById('massaMuscular').getContext('2d');
      const ctx6 = document.getElementById('circunferencia_cintura').getContext('2d');
      const ctx7 = document.getElementById('circunferencia_quadril').getContext('2d');
      const ctx8 = document.getElementById('circunferencia_braco_relaxado').getContext('2d');
      const ctx9 = document.getElementById('circunferencia_braco_contraido').getContext('2d');
      const ctx10 = document.getElementById('circunferencia_peito').getContext('2d');
      const ctx11 = document.getElementById('circunferencia_coxa').getContext('2d');
      const ctx12 = document.getElementById('circunferencia_panturrilha').getContext('2d');

      const chartLabels = @json($labels);
      const chartPesoData = @json($pesoData);
      const chartAlturaData = @json($alturaData);
      const chartImcData = @json($imcData);
      const chartGorduraData = @json($gorduraData);
      const chartMassaMuscularData = @json($massaMuscularData);
      const chartCircunferenciaCinturaData = @json($circunferenciaCinturaData);
      const chartCircunferenciaQuadrilData = @json($circunferenciaQuadrilData);
      const chartCircunferenciaBracoRelaxadoData = @json($circunferenciaBracoRelaxadoData);
      const chartCircunferenciaBracoContraidoData = @json($circunferenciaBracoContraidoData);
      const chartCircunferenciaPeitoData = @json($circunferenciaPeitoData);
      const chartCircunferenciaCoxaData = @json($circunferenciaCoxaData);
      const chartCircunferenciaPanturrilhaData = @json($circunferenciaPanturrilhaData);

      // --- CRIAÇÃO DO GRÁFICO ---
      const peso = new Chart(ctx, {
         type: 'line',
         data: {
            labels: chartLabels,
            datasets: [{
               label: 'Peso (kg)',
               data: chartPesoData,
               borderColor: 'rgba(255, 229, 0, 0.9)',
               backgroundColor: 'rgba(255, 255, 255, 0.1)',
               fill: true,
               tension: 0.3
            }, ]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: {
                  labels: {
                     color: 'white'
                  }
               }
            },
            scales: {
               y: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               },
               x: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               }
            }
         }
      });
      const altura = new Chart(ctx2, {
         type: 'line',
         data: {
            labels: chartLabels,
            datasets: [{
               label: 'Altura (m)',
               data: chartAlturaData,
               borderColor: 'rgba(255, 229, 0, 0.9)',
               backgroundColor: 'rgba(255, 255, 255, 0.1)',
               fill: true,
               tension: 0.3
            }, ]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: {
                  labels: {
                     color: 'white'
                  }
               }
            },
            scales: {
               y: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               },
               x: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               }
            }
         }
      });
      const imc = new Chart(ctx3, {
         type: 'line',
         data: {
            labels: chartLabels,
            datasets: [{
               label: 'IMC',
               data: chartImcData,
               borderColor: 'rgba(255, 229, 0, 0.9)',
               backgroundColor: 'rgba(255, 255, 255, 0.1)',
               fill: true,
               tension: 0.3
            }, ]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: {
                  labels: {
                     color: 'white'
                  }
               }
            },
            scales: {
               y: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               },
               x: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               }
            }
         }
      });
      const gordura = new Chart(ctx4, {
         type: 'line',
         data: {
            labels: chartLabels,
            datasets: [{
               label: 'Gordura Corporal (%)',
               data: chartGorduraData,
               borderColor: 'rgba(255, 229, 0, 0.9)',
               backgroundColor: 'rgba(255, 255, 255, 0.1)',
               fill: true,
               tension: 0.3
            }, ]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: {
                  labels: {
                     color: 'white'
                  }
               }
            },
            scales: {
               y: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               },
               x: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               }
            }
         }
      });
      const massaMuscular = new Chart(ctx5, {
         type: 'line',
         data: {
            labels: chartLabels,
            datasets: [{
               label: 'Massa Muscular (kg)',
               data: chartMassaMuscularData,
               borderColor: 'rgba(255, 229, 0, 0.9)',
               backgroundColor: 'rgba(255, 255, 255, 0.1)',
               fill: true,
               tension: 0.3
            }, ]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: {
                  labels: {
                     color: 'white'
                  }
               }
            },
            scales: {
               y: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               },
               x: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               }
            }
         }
      });
      const circunferenciaCintura = new Chart(ctx6, {
         type: 'line',
         data: {
            labels: chartLabels,
            datasets: [{
               label: 'Circunferência Cintura (cm)',
               data: chartCircunferenciaCinturaData,
               borderColor: 'rgba(255, 229, 0, 0.9)',
               backgroundColor: 'rgba(255, 255, 255, 0.1)',
               fill: true,
               tension: 0.3
            }, ]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: {
                  labels: {
                     color: 'white'
                  }
               }
            },
            scales: {
               y: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               },
               x: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               }
            }
         }
      });
      const circunferenciaQuadril = new Chart(ctx7, {
         type: 'line',
         data: {
            labels: chartLabels,
            datasets: [{
               label: 'Circunferência Quadril (cm)',
               data: chartCircunferenciaQuadrilData,
               borderColor: 'rgba(255, 229, 0, 0.9)',
               backgroundColor: 'rgba(255, 255, 255, 0.1)',
               fill: true,
               tension: 0.3
            }, ]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: {
                  labels: {
                     color: 'white'
                  }
               }
            },
            scales: {
               y: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               },
               x: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               }
            }
         }
      });
      const circunferenciaBracoRelaxado = new Chart(ctx8, {
         type: 'line',
         data: {
            labels: chartLabels,
            datasets: [{
               label: 'Circunferência Braço Relaxado (cm)',
               data: chartCircunferenciaBracoRelaxadoData,
               borderColor: 'rgba(255, 229, 0, 0.9)',
               backgroundColor: 'rgba(255, 255, 255, 0.1)',
               fill: true,
               tension: 0.3
            }, ]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: {
                  labels: {
                     color: 'white'
                  }
               }
            },
            scales: {
               y: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               },
               x: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               }
            }
         }
      });
      const circunferenciaBracoContraido = new Chart(ctx9, {
         type: 'line',
         data: {
            labels: chartLabels,
            datasets: [{
               label: 'Circunferência Braço Contraído (cm)',
               data: chartCircunferenciaBracoContraidoData,
               borderColor: 'rgba(255, 229, 0, 0.9)',
               backgroundColor: 'rgba(255, 255, 255, 0.1)',
               fill: true,
               tension: 0.3
            }, ]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: {
                  labels: {
                     color: 'white'
                  }
               }
            },
            scales: {
               y: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               },
               x: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               }
            }
         }
      });
      const circunferenciaPeito = new Chart(ctx10, {
         type: 'line',
         data: {
            labels: chartLabels,
            datasets: [{
               label: 'Circunferência Peito (cm)',
               data: chartCircunferenciaPeitoData,
               borderColor: 'rgba(255, 229, 0, 0.9)',
               backgroundColor: 'rgba(255, 255, 255, 0.1)',
               fill: true,
               tension: 0.3
            }, ]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: {
                  labels: {
                     color: 'white'
                  }
               }
            },
            scales: {
               y: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               },
               x: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               }
            }
         }
      });
      const circunferenciaCoxa = new Chart(ctx11, {
         type: 'line',
         data: {
            labels: chartLabels,
            datasets: [{
               label: 'Circunferência Coxa (cm)',
               data: chartCircunferenciaCoxaData,
               borderColor: 'rgba(255, 229, 0, 0.9)',
               backgroundColor: 'rgba(255, 255, 255, 0.1)',
               fill: true,
               tension: 0.3
            }, ]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: {
                  labels: {
                     color: 'white'
                  }
               }
            },
            scales: {
               y: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               },
               x: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               }
            }
         }
      });
      const circunferenciaPanturrilha = new Chart(ctx12, {
         type: 'line',
         data: {
            labels: chartLabels,
            datasets: [{
               label: 'Circunferência Panturrilha (cm)',
               data: chartCircunferenciaPanturrilhaData,
               borderColor: 'rgba(255, 229, 0, 0.9)',
               backgroundColor: 'rgba(255, 255, 255, 0.1)',
               fill: true,
               tension: 0.3
            }, ]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
               legend: {
                  labels: {
                     color: 'white'
                  }
               }
            },
            scales: {
               y: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               },
               x: {
                  ticks: {
                     color: 'white'
                  },
                  grid: {
                     color: 'rgba(255, 255, 255, 0.2)'
                  }
               }
            }
         }
      });
   })
</script>
@stop