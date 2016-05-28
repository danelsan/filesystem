
mkdir -p Folder/test1/test2/test3
mkdir -p Folder/test1/test4/test5

touch Folder/test1/test.file1
touch Folder/test1/test2/test.file2

phpunit -c phpunit.xml

