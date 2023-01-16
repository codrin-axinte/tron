
@foreach($data as $result)

    <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Interest</th>
                <th>Principal</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>{{ $result['day'] }}</td>
                <td>{{ $result['interest'] }}</td>
                <td>{{ $result['principal'] }}</td>
            </tr>
        </tbody>
    </table>

@endforeach
