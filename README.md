Version Controller
==================
**not developped now**

Abstract Object Version Controller

The purpose of this project is to have a version history of an abstract object.

## Example
Objects :
* Object 1:
  * type : Domain
  * id : mapado.com
* Object 2:
  * type : RawActivity
  * id : a8eiu82s5
* Object 3:
  * type : FinalActivity
  * id : 25

Tasks :
* Task 1: parsing
* Task 2: datamining.date
* Task 3: datamining.place

Versions : 
<table>
<tr>
<th>Object</th>
<th>Task</th>
<th>Version</th>
</tr>

<tr>
<td>1</td><td>1</td><td>v1.2.0</td>
</tr>
<tr>
<td>2</td><td>2</td><td>v1.1.3</td>
</tr>
<tr>
<td>2</td><td>3</td><td>v2.1</td>
</tr>
<tr>
<td>3</td><td>2</td><td>v1.2</td>
</tr>
<tr>
<td>3</td><td>3</td><td>v2.1.0</td>
</tr>
</table>

## API
### Get a version number
#### Parameters
```json
{
    "object": 1,
    "task": 1
}
```

#### Returns
```json
{
    "version": "v1.2.0"
}
```

### Get all tasks and version for an object
#### Parameters 
```json
{
    "object": 2
}
```

#### Returns 
```json
[
    {
        "task": 2,
        "version": "v1.1.3"
    },
    {
        "task": 3,
        "version": "v2.1"
    }
]
```

### Get all objects and version for a task
#### Parameters 
```json
{
    "task": 2
}
```

#### Returns 
```json
[
    {
        "object": 2,
        "version": "v1.1.3"
    },
    {
        "object": 3,
        "version": "v1.2.0"
    }
]
```

### Get all objects for a task and a version
#### Parameters 
```json
{
    "task": 2,
    "version" : "v1.1.3"
}
```

#### Returns 
```json
{
    "object": 2
}
```

#### Parameters 
```json
{
    "task": 2,
    "version" : "> v1.1.5"
}
```

#### Returns 
```json
{
    "object": 3,
    "version": "v1.2.0"
}
```

### Validate an object for a task and version number
#### Parameters 
```json
{
    "Object": 3,
    "task": 2,
    "version" : "> v1.1.3"
}
```

#### Returns 
True 

#### Parameters 
```json
{
    "Object": 3,
    "task": 3,
    "version" : "v2.1.2"
}
```

#### Returns 
False
