# Documentation
Here is a list of methods that are supported by this library

## Notification class methods
General methods are ones that will be used more often. Service specific methods are implemented here so that those can be used if needed. But be warn when using specific methods to check for service before you use them. Library implements an exception when trying to access unsupported method.

### General available methods
Supported methods:
* sendMessage(Message $message)
* getService()
* setNotificationTTL($ttl)
* checkPayload(Message $message)

### APNS - Apple Push Notification Server
> To learn more about this service go to [official APNS site](https://developer.apple.com/library/ios/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/Chapters/ApplePushService.html).

Supported methods:
* setIdentifier($identifier)
* setPriority($priority)

### GCM - Google Cloud Messaging
> To learn more about this service go to [official GCM site](https://developers.google.com/cloud-messaging/).

Supported methods:
* setRestrictedPackageName($restrictedPackageName)
