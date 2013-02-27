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
  * type : raw\_activity
  * id : a8eiu82s5
* Object 3:
  * type : final\_activity
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
    "task_name": "datamining.date",
    "versionnable_id": 25,
    "versionnable_type": "final_activity"
}
```

#### Returns
```json
{
    "complete": "v1.2.0",
    "major": "1",
    "minor": "2",
    "maintenance": "0"
}
```

### Get all tasks and version for an object
#### Parameters 
```json
{
    "versionnable_id": 25,
    "versionnable_type": "final_activity"
}
```

#### Returns 
```json
[
    {
        "version_number": {
            "complete": "1.2.0",
            "major": "1",
            "minor": "2",
            "maintenance": "0"
        },
        "task_name": "datamining.date",
        "versionnable": {
            "id": "25",
            "type": "final_activity"
        }
    },
    {
        "version_number": {
            "complete": "2.1.0",
            "major": "2",
            "minor": "1",
            "maintenance": "0"
        },
        "task_name": "datamining.place",
        "versionnable": {
            "id": "25",
            "type": "final_activity"
        }
    }
]
```

### Get all objects and version for a task
#### Parameters 
```json
{
    "task_name": "datamining.place"
}
```

#### Returns 
```json
[
    {
        "version_number": {
            "complete": "1.1.3",
            "major": 1,
            "minor": 1,
            "maintenance": 3
        },
        "task_name": "datamining.date",
        "versionnable": {
            "id": "a8eiu82s5",
            "type": "raw_activity"
        }
    },
    {
        "version_number": {
            "complete": "1.2.0",
            "major": 1,
            "minor": 2,
            "maintenance": 0
        },
        "task_name": "datamining.date",
        "versionnable": {
            "id": "25",
            "type": "final_activity"
        }
    }
]
```

### Get all objects for a task and a version
#### Parameters 
```json
{
    "task_name": "datamining.place",
    "version" : "> v1.1.5"
}
```

#### Returns 
```json
{
    "1": {
        "version_number": {
            "complete": "1.2.0",
            "major": 1,
            "minor": 2,
            "maintenance": 0
        },
        "task_name": "datamining.date",
        "versionnable": {
            "id": "25",
            "type": "final_activity"
        }
    }
}
```

#### Parameters 
```json
{
    "task_name": "datamining.place",
    "version" : "= 1.2"
}
```

#### Returns 
```json
{
    "1": {
        "version_number": {
            "complete": "1.2.0",
            "major": 1,
            "minor": 2,
            "maintenance": 0
        },
        "task_name": "datamining.date",
        "versionnable": {
            "id": "25",
            "type": "final_activity"
        }
    }
}
```

### Validate an object for a task and version number
#### Parameters 
```json
{
    "task_name": "datamining.place",
    "versionnable_id": 25,
    "versionnable_type": "final_activity",
    "version" : "> v2"
}
```

#### Returns 
```json
{
    "is_valid": true
}
```

#### Parameters 
```json
{
    "task_name": "datamining.place",
    "versionnable_id": 25,
    "versionnable_type": "final_activity",
    "version" : "<= v1.5.0"
}
```

#### Returns 
```json
{
    "is_valid": false
}
```
