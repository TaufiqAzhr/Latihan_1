<nav class="navbar navbar-expand-lg bg-primary navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="#">POLGAN</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
          <a class="nav-link active" aria-current="page" href="/dashboard">Dashboard</a>
          <a class="nav-link active" href="/users">Users</a>
          <a class="nav-link active" href="/products">Products</a>
    </div>
    <div class="ms-auto navbar-nav">
      <form action="/logout" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
      </form>
    </div>
    </div>
  </div>
</nav>