# Migrating from WoltLab Suite 5.5 - Comments

In WoltLab Suite 6.0 the comment system has been overhauled.
In the process, the integration of comments via templates has been significantly simplified:

```smarty
{include file='comments' commentContainerID='someElementId' commentObjectID=$someObjectID}
```

An example for the migration of existing template integrations can be found [here](https://github.com/WoltLab/WCF/commit/b1d5f7cc6b81ae7fd938603bb20a3a454a531a96#diff-3419ed2f17fa84a70caf0d99511d5ac2a7704c62f24cc7042984d7a9932525ce).

See [WoltLab/WCF#5210](https://github.com/WoltLab/WCF/pull/5210) for more details.
