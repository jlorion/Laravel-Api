##SETUP
requirements 
 - docker

go to the application directory 
```bash
cd ./Laravel-Api
```
run command 
```bash
docker --build -d .
```
make sure it is running
```bash
docker ps
```
run server
```bash
docker exec container_name composer run dev
```

