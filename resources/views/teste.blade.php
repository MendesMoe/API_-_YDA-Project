<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div class="cont">
        <table>
            <th>
            <td>Name</td>
            <td>ID</td>
            <td>Next</td>
            <td>Previous</td>
            </th>
            @foreach ($firms as $firm)
                <tr>
                    <td> - </td>
                    <td>
                        {{ $firm->name ?? '?' }}
                    </td>
                    <td>
                        {{ $firm->id ?? '?' }}
                    </td>
                    <td>
                        {{ $firm->next ?? '?' }}
                    </td>
                    <td>
                        {{ $firm->previous ?? '?' }}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</body>

</html>

<style>
    .cont {
        width: 100vw;
        height: 100vh;
        margin-top: 5%;
        padding: 5%;
        background-color: rgb(220, 217, 217);
    }
</style>
