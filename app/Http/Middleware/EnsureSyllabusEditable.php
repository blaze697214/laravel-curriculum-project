<?php

namespace App\Http\Middleware;

use App\Models\Syllabus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSyllabusEditable
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    // app/Http/Middleware/EnsureSyllabusEditable.php

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('get')) {
            $courseId = $request->route('course');

            $syllabus = Syllabus::where('course_master_id', $courseId)->first();

            if ($syllabus && in_array($syllabus->status, [
                'submitted',
                'moderator_approved',
                'hod_approved',
            ])) {
                return redirect()
                    ->back()
                    ->withErrors('Syllabus is locked. Status: '.$syllabus->status);
            }
        }

        return $next($request);
    }
}
