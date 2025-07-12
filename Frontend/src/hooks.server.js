/** @type {import('@sveltejs/kit').Handle} */
export async function handle({ event, resolve }) {
  if (event.url.pathname.startsWith('/.well-known/appspecific')) {
    return new Response(null, { status: 404 });
  }
  return await resolve(event);
}