<div class="text-center mb-7">

    <h2 class="text-base font-bold  tracking-wide">
        PROGRAMME STRUCTURE
    </h2>

    <h3 class="text-base font-semibold  mt-1">
        SCHEME AT A GLANCE
    </h3>

    <h3 class="text-base font-medium  mt-1">
        PROGRAMME - {{ strtoupper($department->name) }}
    </h3>

</div>


<div class="bg-white overflow-hidden">
        <table class="w-full text-sm text-center border-collapse">

            {{-- HEADER --}}
            <thead class="bg-gray-100 text-gray-700">

                <tr>

                    <th class="border border-gray-400 px-4 py-3 font-semibold whitespace-nowrap">
                        Course Type
                    </th>

                    <th class="border border-gray-400 px-4 py-3 font-semibold min-w-">
                        Name of Course Type
                    </th>

                    <th class="border border-gray-400 px-4 py-1 font-semibold whitespace-nowrap">
                        Total Number<br>
                        of Courses<br>
                        Offered
                    </th>

                    <th class="border border-gray-400 px-4 py-3 font-semibold whitespace-nowrap">
                        Number of Courses<br>
                        to be Completed
                    </th>

                    <th class="border border-gray-400 px-4 py-3 font-semibold">
                        TH
                    </th>

                    <th class="border border-gray-400 px-4 py-3 font-semibold">
                        TU
                    </th>

                    <th class="border border-gray-400 px-4 py-3 font-semibold">
                        PR
                    </th>

                    <th class="border border-gray-400 px-4 py-3 font-semibold whitespace-nowrap">
                        Total<br>
                        Hours
                    </th>

                    <th class="border border-gray-400 px-4 py-3 font-semibold whitespace-nowrap">
                        Total<br>
                        Credits
                    </th>

                    <th class="border border-gray-400 px-4 py-3 font-semibold">
                        Marks
                    </th>

                </tr>

            </thead>


            {{-- BODY --}}
            <tbody class="divide-y divide-gray-100">

                {{-- CATEGORY ROWS --}}
                @foreach ($categories as $row)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="border border-gray-400 px-4 py-2 font-semibold text-gray-700">
                            {{ $row->courseCategory->abbreviation }}
                        </td>

                        <td class="border border-gray-400 px-4 py-2 text-left text-gray-700">
                            {{ $row->courseCategory->name }}
                        </td>

                        <td class="border border-gray-400 px-4 py-2">
                            {{ $row->courses_offered }}
                        </td>

                        <td class="border border-gray-400 px-4 py-2">
                            {{ $row->courses_to_complete }}
                        </td>

                        <td class="border border-gray-400 px-4 py-2">
                            {{ $row->th_hrs }}
                        </td>

                        <td class="border border-gray-400 px-4 py-2">
                            {{ $row->tu_hrs }}
                        </td>

                        <td class="border border-gray-400 px-4 py-2">
                            {{ $row->pr_hrs }}
                        </td>

                        <td class="border border-gray-400 px-4 py-2">
                            {{ $row->th_hrs + $row->tu_hrs + $row->pr_hrs }}
                        </td>

                        <td class="border border-gray-400 px-4 py-2 font-semibold text-gray-700">
                            {{ $row->credits }}
                        </td>

                        <td class="border border-gray-400 px-4 py-2 font-semibold text-gray-700">
                            {{ $row->marks }}
                        </td>

                    </tr>
                @endforeach


                {{-- TOTAL ROW --}}
                <tr class="bg-gray-50 font-semibold text-gray-800">

                    <td colspan="2" class="border border-gray-400 px-4 py-2 text-lg">

                        Total

                    </td>

                    <td class="border border-gray-400 px-4 py-2">
                        {{ $totals['offered'] }}
                    </td>

                    <td class="border border-gray-400 px-4 py-0.5 leading-6">

                        <span class="text-gray-700">
                            {{ $compulsoryCompleted }}
                        </span>
                        compulsory

                        <br>

                        <span class="text-gray-700">
                            +{{ $electiveCompleted }}
                        </span>
                        Elective

                        <br>

                        <span class="font-bold text-gray-700">
                            = {{ $totals['completed'] }}
                        </span>

                    </td>

                    <td class="border border-gray-400 px-4 py-2">
                        {{ $totals['th'] }}
                    </td>

                    <td class="border border-gray-400 px-4 py-2">
                        {{ $totals['tu'] }}
                    </td>

                    <td class="border border-gray-400 px-4 py-2">
                        {{ $totals['pr'] }}
                    </td>

                    <td class="border border-gray-400 px-4 py-2">
                        {{ $totals['hours'] }}
                    </td>

                    <td class="border border-gray-400 px-4 py-2 text-gray-700">
                        {{ $totals['credits'] }}
                    </td>

                    <td class="border border-gray-400 px-4 py-2 text-gray-700">
                        {{ $totals['marks'] }}
                    </td>

                </tr>


                {{-- EXIT COURSES --}}
                @foreach ($exitCourses as $exit)
                    <tr class=" hover:bg-gray-100 transition">

                        <td colspan="2"
                            class="border border-gray-400 px-4 py-2 font-semibold text-center text-gray-700">

                            {{ $exit->title }}

                        </td>

                        <td class="border border-gray-400 px-4 py-2">
                            {{ $exit->courses_offered ?? '--' }}
                        </td>

                        <td class="border border-gray-400 px-4 py-2">
                            {{ $exit->courses_to_complete ?? '--' }}
                        </td>

                        <td class="border border-gray-400 px-4 py-2">
                            {{ $exit->th_hrs ?? '--' }}
                        </td>

                        <td class="border border-gray-400 px-4 py-2">
                            {{ $exit->tu_hrs ?? '--' }}
                        </td>

                        <td class="border border-gray-400 px-4 py-2">
                            {{ $exit->pr_hrs ?? '--' }}
                        </td>

                        <td class="border border-gray-400 px-4 py-2">
                            {{ $exit->th_hrs + $exit->tu_hrs + $exit->pr_hrs ?? '--' }}
                        </td>

                        <td class="border border-gray-400 px-4 py-2 font-semibold text-gray-700">
                            {{ $exit->credits ?? '--' }}
                        </td>

                        <td class="border border-gray-400 px-4 py-2 font-semibold text-gray-700">
                            {{ $exit->marks ?? '--' }}
                        </td>

                    </tr>
                @endforeach


                {{-- GRAND TOTAL --}}
                <tr class="font-semibold">

                    <td colspan="2" class="border border-gray-400 px-4 py-1.5 text-lg">

                        Grand Total

                    </td>

                    <td class="border border-gray-400 px-4 py-1.5">
                        {{ $grand['offered'] }}
                    </td>

                    <td class="border border-gray-400 px-4 py-1.5">
                        {{ $grand['completed'] }}
                    </td>

                    <td class="border border-gray-400 px-4 py-1.5">
                        {{ $grand['th'] }}
                    </td>

                    <td class="border border-gray-400 px-4 py-1.5">
                        {{ $grand['tu'] }}
                    </td>

                    <td class="border border-gray-400 px-4 py-1.5">
                        {{ $grand['pr'] }}
                    </td>

                    <td class="border border-gray-400 px-4 py-1.5">
                        {{ $grand['hours'] }}
                    </td>

                    <td class="border border-gray-400 px-4 py-1.5">
                        {{ $grand['credits'] }}
                    </td>

                    <td class="border border-gray-400 px-4 py-1.5">
                        {{ $grand['marks'] }}
                    </td>

                </tr>

            </tbody>

        </table>

    

</div>
