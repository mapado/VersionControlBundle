Version Controller Webservice
==================

A webservice is available.

Here are all the command list:

## Version Number
Url :
```
/version_number/{taskName}/{versionnableType}:{versionnableId}
```
Example :
```
http://versions.example.com/version_number/datamining.date/final_object_type:25
```

## Task list
Url :
```
/task_list/{versionnableType}:{versionnableId}
```
Example :
```
http://versions.example.com/task_list/final_object_type:25
```


## Object list
Url :
```
/object_list/{taskName}
```
Example :
```
http://versions.example.com/object_list/datamining.date
```

## Object list with version
Url :
```
/object_list/{taskName}/{operator}{versionComplete}
```
Example :
```
http://versions.example.com/object_list/datamining.date/>=1.0
```

## Check validity
Url :
```
/is_valid/{taskName}/{versionnableType}:{versionnableId}/{operator}{versionComplete}
```
Example :
```
http://versions.example.com/is_valid/datamining.date/final_object_type:25/< 1.3
```

## Delete a task / object
Url :
```
/delete/{taskName}/{versionnableType}:{versionnableId}
```
Example :
```
http://versions.example.com/delete/datamining.date/final_object_type:25
```

## Update a task / object
Url :
```
/update/{taskName}/{versionnableType}:{versionnableId}/{versionComplete}
```
Example :
```
http://versions.example.com/update/datamining.date/final_object_type:25/2.1.0
```
