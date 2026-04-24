<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results System - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-indigo-50 min-h-screen">
    <!-- Navbar / Header -->
    <header class="py-12 bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight">Student Results Tracking</h1>
            <p class="mt-4 text-xl text-indigo-100 max-w-2xl mx-auto">
                Quickly access and monitor academic performance across all university courses.
            </p>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
        <!-- Error Alert -->
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-md animate-bounce">
                <p class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ session('error') }}
                </p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Instructions Column -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl p-8 sticky top-8 border border-indigo-100">
                    <h2 class="text-2xl font-bold text-slate-800 mb-6">How to Search?</h2>
                    <ul class="space-y-6">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 bg-indigo-600 text-white rounded-full h-8 w-8 flex items-center justify-center font-bold mr-4">1</span>
                            <p class="text-slate-600">Browse the list of available courses and click on <strong>Search Results</strong>.</p>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 bg-indigo-600 text-white rounded-full h-8 w-8 flex items-center justify-center font-bold mr-4">2</span>
                            <p class="text-slate-600">Enter your unique <strong>University ID</strong> in the search box.</p>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 bg-indigo-600 text-white rounded-full h-8 w-8 flex items-center justify-center font-bold mr-4">3</span>
                            <p class="text-slate-600">View your scores for Midterms, Finals, and other activities immediately.</p>
                        </li>
                    </ul>

                    <div class="mt-10 p-4 bg-indigo-50 rounded-xl border border-indigo-100 text-sm text-indigo-700">
                        <p><strong>Note:</strong> If you don't find your results, please contact the course coordinator.</p>
                    </div>
                </div>
            </div>

            <!-- Courses Column -->
            <div class="lg:col-span-2">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-slate-800">Available Courses</h2>
                    <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                        {{ $courses->count() }} Total
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($courses as $course)
                        <div class="group bg-white rounded-2xl shadow-sm border border-slate-200 p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            <div class="flex justify-between items-start mb-4">
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-1 rounded uppercase">
                                    {{ $course->semester->name ?? 'N/A' }}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                                {{ $course->name }}
                            </h3>
                            <div class="mt-6">
                                <a href="{{ route('results.index', $course->id) }}" 
                                   class="inline-flex items-center justify-center w-full bg-indigo-50 text-indigo-700 font-semibold py-2.5 rounded-xl border border-indigo-100 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all duration-300 shadow-sm">
                                    Search Results
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($courses->isEmpty())
                    <div class="text-center py-20 bg-white rounded-2xl shadow-sm border border-dashed border-slate-300">
                        <p class="text-slate-400">No courses available at the moment.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <footer class="mt-20 py-10 bg-slate-900 text-slate-400">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Student Results Management System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
