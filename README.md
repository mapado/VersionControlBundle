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
| Object | Task | Version |
| ------ | ---- | ------- |
| 1 | 1 | v1.2.0 |
| 2 | 2 | v1.1.3 |
| 2 | 3 | v2.1 |
| 3 | 2 | v1.2 |
| 3 | 3 | v2.1.0 |


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
