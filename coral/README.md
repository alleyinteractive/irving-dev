# Coral Development

The Irving Dev environment includes everything you need to run a local instance of [Coral](https://docs.coralproject.net/coral/), an open source commenting platform.

## Requirements

The Coral project requires Docker to be installed and running. Node.js and NPM are required.

## Usage Instructions

### Installing Coral

This will create the directories needed for running Coral locally:

```
npm run coral install
```

### Starting Coral

This will start up a Docker container running a Coral instance at http://localhost:3000/.

```
npm run coral start
```

### Stopping Coral

```
npm run coral stop
```

## Setting up Coral for Irving/WordPress

After installing Coral for the first time, you will need to visit http://localhost:3000/admin and go through the install wizard for setting up your Coral instance. Your site URL list (i.e. Site permitted domains) should include the URL for the front end of your Irving application. Feel free to configure the rest of the Coral settings to your liking.

In the WordPress admin, you will need to add the Coral URL and SSO Secret (found on http://localhost:3000/admin/configure/auth) to the Irving Integrations settings page.
