<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results - {{ $course->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <!-- Navigation & Error Alert -->
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Courses
            </a>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">
                {{ $course->name }}
            </h1>
            <p class="mt-3 text-lg text-slate-500">
                Search for student results using University ID
            </p>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 mb-8">
            <form action="{{ route('results.search', $course->id) }}" method="POST">
                @csrf
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-grow">
                        <label for="university_id" class="sr-only">University ID</label>
                        <input type="text" 
                               name="university_id" 
                               id="university_id" 
                               value="{{ old('university_id', $university_id ?? '') }}"
                               placeholder="Enter University ID (e.g., 2024001)" 
                               class="block w-full rounded-xl border-slate-300 px-4 py-3 text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('university_id') border-red-500 ring-1 ring-red-500 @enderror"
                               required>
                        
                        @error('university_id')
                            <p class="mt-2 text-sm text-red-600" id="university_id-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" 
                            class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        Search Results
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Display Logic -->
        @if(isset($results))
            @if($results->isNotEmpty())
                <!-- Found Results -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                        <h2 class="text-sm font-medium text-slate-500 uppercase tracking-wider">Student Information</h2>
                        <p class="text-xl font-bold text-slate-900 mt-1">
                            {{ $results->first()->student->name }}
                        </p>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50">
                                    <th class="px-8 py-4 text-sm font-semibold text-slate-700">Activity Type</th>
                                    <th class="px-8 py-4 text-sm font-semibold text-slate-700 text-right">Score</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($results as $activity)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-8 py-4 text-slate-700 font-medium">{{ $activity->activity_type }}</td>
                                        <td class="px-8 py-4 text-right">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-sm font-medium {{ $activity->score >= 50 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                                {{ number_format($activity->score, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <!-- No Results Found -->
                <div class="bg-red-50 border border-red-200 rounded-2xl p-6 flex items-center text-red-800 animate-in zoom-in duration-300">
                    <svg class="h-6 w-6 text-red-500 mr-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    <p class="font-medium">No results found for this University ID ({{ $university_id }}).</p>
                </div>
            @endif
        @endif

        <!-- Footer -->
        <p class="mt-12 text-center text-sm text-slate-400">
            &copy; {{ date('Y') }} Student Results Management System
        </p>
    </div>
</body>
</html>
