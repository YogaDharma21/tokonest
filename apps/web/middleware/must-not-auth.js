export default defineNuxtRouteMiddleware(async () => {
  const session = useSession();

  if (session.tokenCookie || session.token) {
    return navigateTo("/my-account/profile");
  }
});