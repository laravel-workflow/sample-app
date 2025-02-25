# Laravel Workflow Sample App

This is a sample Laravel 12 application with example workflows that you can run inside a GitHub codespace.

### Step 1
Create a codespace from the main branch of this repo.

<img src="https://user-images.githubusercontent.com/1130888/233664377-f300ad50-5436-4bb8-b172-c52e12047264.png" alt="image" width="300">

### Step 2
Once the codespace has been created, wait for the codespace to build. This should take between 5 to 10 minutes.


### Step 3
Once it is done. You will see the editor and the terminal at the bottom.

<img src="https://user-images.githubusercontent.com/1130888/233665550-1a4f2098-2919-4108-ac9f-bef1a9f2f47c.png" alt="image" width="400">

### Step 4
Run the init command to create the necessary database tables and dependencies.

```bash
php artisan app:init
```

### Step 5
Start the queue worker. This will enable the processing of workflows and activities.

```bash
php artisan queue:work
```

### Step 6
Create a new terminal window.

<img src="https://user-images.githubusercontent.com/1130888/233666917-029247c7-9e6c-46de-b304-27473fd34517.png" alt="image" width="200">

### Step 7
Start the example workflow inside the new terminal window.

```bash
php artisan app:workflow
```

### Step 8
You can view the waterline dashboard via the mapped port.

<img src="https://user-images.githubusercontent.com/1130888/233668485-b988e336-0462-4bbc-bb77-78c73df363b4.png" alt="image" width="500">

Add `/waterline/dashboard` to the URL e.g. `https://[your-codespace-name]-80.preview.app.github.dev/waterline/dashboard`

<img src="https://user-images.githubusercontent.com/1130888/233669600-3340ada6-5f73-4602-8d82-a81a9d43f883.png" alt="image" width="600">

### Step 9
Run the workflow and activity tests.

```bash
php artisan test
```

That's it! You can now create and test workflows.
